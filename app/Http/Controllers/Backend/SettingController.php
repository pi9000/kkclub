<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Settings;
use Illuminate\Support\Facades\Http;
use App\Models\Contact;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\ApiProvider;
use App\Models\DomainList;
use App\Models\Banner;
use App\Models\User;
use App\Models\Bonus;
use Illuminate\Support\Facades\File;

class SettingController extends Controller
{
    public function index(Request $request)
    {
        $web = Settings::where('agent_id', $request->agent_id)->first();
        $contact = Contact::where('agent_id', $request->agent_id)->first();
        $api = DB::table('api_providers')->where('agent_id', $request->agent_id)->get();
        return response()->json([
            'status' => 'success',
            'web' => $web,
            'contact' => $contact,
            'api' => $api,
        ]);
    }

    public function update(Request $request)
    {
        $web = Settings::where('agent_id', $request->agent_id)->first();
        $contact = Contact::where('agent_id', $request->agent_id)->first();
        $web->logo = $request->logo;
        $web->min_depo = $request->min_depo;
        $web->min_wd = $request->min_wd;
        $web->title = $request->title;
        $web->judul = $request->judul;
        $web->deskripsi = $request->deskripsi;
        $web->keyword = $request->keyword;
        $web->icon_web = $request->icon_web;
        $web->notif_bar = $request->notif_bar;
        $contact->no_whatsapp = $request->no_whatsapp;
        $contact->script = $request->script;
        $web->tutorial_withdraw = $request->tutorial_withdraw;
        $web->tutorial_register = $request->tutorial_register;
        $web->tutorial_deposit = $request->tutorial_deposit;
        $web->home_footer = $request->home_footer;
        $web->gateway_merchant = $request->home_footer;
        $web->gateway_apikey = $request->gateway_apikey;
        $web->gateway_secretkey = $request->gateway_secretkey;
        $web->gateway_endpoint = $request->gateway_endpoint;
        $web->telegram_chat_id = $request->telegram_chat_id;
        $web->costum_script = $request->costum_script;
        $web->save();
        $contact->save();
        return response()->json([
            'status' => 'success',
            'message' => 'Website Successfully updated',
        ]);
    }

    public function edit_api($id, Request $request)
    {
        $apiss = ApiProvider::find($id);
        if (!$apiss) {
            return response()->json([
                'status' => 'error',
                'message' => 'API Provider not found',
            ], 404);
        }
        $apiss->apikey = $request->apikey;
        $apiss->secretkey = $request->secretkey;
        $apiss->agentcode = $request->agentcode;
        $apiss->token = $request->agentcode;
        $apiss->url = $request->url;
        $apiss->save();

        return response()->json([
            'status' => 'success',
            'message' => 'API Successfully updated',
        ]);
    }

    public function domain_list(Request $request)
    {
        $domains = DomainList::where('agent_id', $request->agent_id)->orderBy('created_at', 'desc')->get();
        return response()->json([
            'status' => 'success',
            'data' => $domains,
        ]);
    }

    public function domain_remove(Request $request, $id)
    {
        $domain = DomainList::where('zone_id', $id)->first();
        if (!$domain) {
            return response()->json([
                'status' => 'error',
                'message' => 'Domain not found',
            ], 404);
        }
        $domain->delete();

        Http::withToken(env('CLOUDFLARE_API_TOKEN'))
            ->delete("https://api.cloudflare.com/client/v4/zones/{$domain->zone_id}");

        return response()->json([
            'status' => 'success',
            'message' => 'Domain Successfully removed',
        ]);
    }

    public function add_domain(Request $request)
    {
        $domain = $request->domain;
        $tenantId = $request->agent_id;

        $domain_check = DomainList::where('agent_id', $tenantId)->count();
        if ($domain_check >= 30) {
            return response()->json([
                'status' => 'error',
                'message' => 'Domain limit exceeded',
            ], 200);
        }

        $addZone = Http::withToken(env('CLOUDFLARE_API_TOKEN'))
            ->post(env('CLOUDFLARE_API_BASE') . '/zones', [
                'name' => $domain,
                'type' => 'full',
                'jump_start' => true
            ]);

        if (!($addZone['success'] ?? false)) {
            return response()->json(['error' => $addZone['errors']], 400);
        }

        $zone = $addZone['result'];

        Http::withToken(env('CLOUDFLARE_API_TOKEN'))
            ->post(env('CLOUDFLARE_API_BASE') . "/zones/{$zone['id']}/dns_records", [
                'type' => 'A',
                'name' => $domain,
                'content' => '194.233.71.101',
                'ttl' => 3600,
                'proxied' => true
            ]);


        Http::withToken(env('CLOUDFLARE_API_TOKEN'))
            ->post(env('CLOUDFLARE_API_BASE') . "/zones/{$zone['id']}/dns_records", [
                'type' => 'A',
                'name' => "www.$domain",
                'content' => '194.233.71.101',
                'ttl' => 3600,
                'proxied' => true
            ]);

        $domainList = new DomainList();
        $domainList->agent_id = $tenantId;
        $domainList->domain = $domain;
        $domainList->zone_id = $zone['id'];
        $domainList->ns1 = $zone['name_servers'][0] ?? null;
        $domainList->ns2 = $zone['name_servers'][1] ?? null;
        $domainList->type = 'main';
        $domainList->save();

        $nginxAvailablePath = "/etc/nginx/sites-available/$domain";
        $nginxEnabledPath = "/etc/nginx/sites-enabled/$domain";
        $webRoot = env('WEB_ROOT_NGINX');

        $nginxConf = "
server {
    listen 80;
    listen [::]:80;
    server_name $domain www.$domain;

    root $webRoot;
    index index.php index.html;

    location / {
        try_files \$uri \$uri/ /index.php?\$query_string;
    }

    error_page 404 /index.php;

    location ~ \.php$ {
        include fastcgi_params;
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME \$document_root\$fastcgi_script_name;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
";


        File::put($nginxAvailablePath, $nginxConf);

        if (!file_exists($nginxEnabledPath)) {
            shell_exec("ln -s $nginxAvailablePath $nginxEnabledPath");
        }

        shell_exec("sudo nginx -t 2>&1");
        shell_exec("sudo systemctl reload nginx");
        shell_exec("sudo certbot --nginx -d $domain");

        return response()->json([
            'status' => 'success',
            'message' => 'Domain successfully added',
        ], 200);
    }

    public function sliding_banner(Request $request)
    {
        $banner = Banner::where('agent_id', $request->agent_id)->get();
        return response()->json([
            'status' => 'success',
            'data' => $banner
        ]);
    }

    public function sliding_banner_create(Request $request)
    {
        $banner = new Banner();
        $banner->agent_id = $request->agent_id;
        $banner->gambar = $request->banner_image;
        $banner->status = 'active';
        $banner->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Banner Created'
        ]);
    }

    public function sliding_banner_delete($id, Request $request)
    {
        $banner = Banner::find($id);
        if (!$banner) {
            return response()->json([
                'status' => 'error',
                'message' => 'Banner not found',
            ], 404);
        }
        $banner->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Banner Successfully deleted',
        ]);
    }

    public function brand_management(Request $request)
    {
        $settings = Settings::get();

        return response()->json([
            'status' => 'success',
            'data' => $settings
        ]);
    }

    public function create_brand(Request $request)
    {
        $settings = Settings::first();
        $web = new Settings();
        $web->agent_id = strtoupper(Str::random(7));
        $web->logo = $settings->logo;
        $web->min_depo = $settings->min_depo;
        $web->min_wd = $settings->min_wd;
        $web->title = $settings->title;
        $web->judul = $request->title;
        $web->deskripsi = $settings->deskripsi;
        $web->keyword = $settings->keyword;
        $web->icon_web = $settings->icon_web;
        $web->notif_bar = $settings->notif_bar;
        $web->tutorial_withdraw = $settings->tutorial_withdraw;
        $web->tutorial_register = $settings->tutorial_register;
        $web->tutorial_deposit = $settings->tutorial_deposit;
        $web->home_footer = $settings->home_footer;
        $web->gateway_merchant =  '-';
        $web->gateway_apikey =  '-';
        $web->gateway_secretkey = '-';
        $web->gateway_endpoint = $settings->gateway_endpoint;
        $web->telegram_chat_id =  '-';
        $web->costum_script = $settings->costum_script;
        $web->url = $settings->url;
        $web->warna = $settings->warna;
        $web->warna = $settings->warna;
        $web->save();

        $api_nexus = ApiProvider::find(1);
        $api_reviplay = ApiProvider::find(2);

        $contact_old = Contact::first();

        $contact = new Contact();
        $contact->agent_id = $web->agent_id;
        $contact->no_whatsapp = $contact_old->no_whatsapp;
        $contact->script = $contact_old->script;
        $contact->save();

        $new_nexus = new ApiProvider();
        $new_nexus->agent_id = $web->agent_id;
        $new_nexus->provider = $api_nexus->provider;
        $new_nexus->type = $api_nexus->type;
        $new_nexus->apikey = $request->apikey_nexusggr;
        $new_nexus->agentcode = $request->agentcode_nexusggr;
        $new_nexus->secretkey = $request->secretkey_nexusggr;
        $new_nexus->token = $request->secretkey_nexusggr;
        $new_nexus->url = $api_nexus->url;
        $new_nexus->status = $api_nexus->status;
        $new_nexus->save();

        $new_reviplay = new ApiProvider();
        $new_reviplay->agent_id = $web->agent_id;
        $new_reviplay->provider = $api_reviplay->provider;
        $new_reviplay->type = $api_reviplay->type;
        $new_reviplay->apikey = $request->apikey_reviplay;
        $new_reviplay->agentcode = $request->agentcode_reviplay;
        $new_reviplay->secretkey = $request->secretkey_reviplay;
        $new_reviplay->token = $request->secretkey_reviplay;
        $new_reviplay->url = $api_reviplay->url;
        $new_reviplay->status = $api_reviplay->status;
        $new_reviplay->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Brand Successfully created',
            'agent_id' => $web->agent_id
        ]);
    }

    public function delete_brand($id, Request $request)
    {
        $check = Settings::where('agent_id', $id)->first();
        if ($check->id == 1) {
            return response()->json([
                'status' => 'error',
                'message' => 'Cannot delete the main brand',
            ], 200);
        }

        ApiProvider::where('agent_id', $id)->delete();
        Settings::where('agent_id', $id)->delete();
        Contact::where('agent_id', $id)->delete();
        Banner::where('agent_id', $id)->delete();
        DomainList::where('agent_id', $id)->delete();
        Bonus::where('agent_id', $id)->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Brand Successfully deleted',
        ], 200);
    }
}
