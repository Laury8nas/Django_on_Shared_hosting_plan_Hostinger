
# Django installation on Hostinger shared hosting plan
In this guide I will show you how I deployed the Django application on the Hostinger shared hosting plan. Keep in mind, that this guide is experimental and it's not a reliable solution to host a Django application. Also, be aware that by following my guide you might violate [Hostinger's Terms of Service](https://www.hostinger.com/legal/universal-terms-of-service-agreement "Hostinger's Terms of Service"). **Everything you do is your responsibility!**

## Preparation

Before going further, make sure to:

- Check if Python3 is installed on your shared hosting server by running this command via SSH: `python3` (Your terminal cursor should change to these signs `>>>` )
- Connect your domain to Cloudflare by following[ this guide](https://support.hostinger.com/en/articles/4741545-how-to-use-cloudflare " this guide").
- Check if the domain's A/CNAME/AAAA record (for the root value) in Cloudflare's DNS zone is deleted.
- Have at least a Premium Web hosting plan or its equivalent

After that, [log in to your shared hosting plan via SSH](https://support.hostinger.com/en/articles/1583245-how-to-connect-to-a-hosting-plan-via-sshhttp:// "login to your shared hosting plan via SSH") and execute this command to be in the public_html folder:
`cd domains/yourdomain.tld/public_html`
Now, download the configuration files and [Django project](https://github.com/mymi14s/Django-Project-Starter-Template/tree/master "Django project") by executing these commands:
`git clone "https://github.com/mymi14s/Django-Project-Starter-Template.git"`
`git clone "https://github.com/Laury8nas/Django_on_Shared_hosting_plan_Hostinger.git"`

Also, make sure to download `Cloudflared` executable files from [official sources](https://developers.cloudflare.com/cloudflare-one/connections/connect-networks/downloads/http:// "official sources") or from [this MEGA link](https://mega.nz/folder/8fcwmA4J#WGhVhxEvnYNTigGdBuqGpA "this MEGA link")(there should be one executable file called "cloudflared" and the other file called "hell", they're the same but you need to download both of them) and upload them in this path: `/home/u0123456789/domains/yourdomain.tld/public_html/Django_on_Shared_hosting_plan_Hostinger`

**replace u0123456789 and yourdomain.tld with your hosting username and your domain name.*

## Django server configuration

To proceed with the Django application deployment, you need to install `pip` packet manager. To do that, in your terminal make sure to be in the correct location:
`cd /home/u0123456789/domains/yourdomain.tld/public_html/Django_on_Shared_hosting_plan_Hostinger`

Now, you can install `pip` packet manager by executing this command:
`python3 get-pip.py`

After that, go into the Django project folder by executing this command:
`cd /home/u0123456789/domains/yourdomain.tld/public_html/Django-Project-Starter-Template/src/
`
Install the required libraries for that Django project:
`cat requirements.txt | xargs -n 1 python3 /home/u0123456789/.local/bin/pip install`

Make migrations:
`python3 manage.py makemigrations user setting`
`python3 manage.py migrate`

Check if the Django server runs without any problems:
`python3 manage.py runserver`

If you see an output similar to this, then you're good to go!:
```
Performing system checks...

System check identified no issues (0 silenced).
February 09, 2024 - 12:22:28
Django version 2.1.4, using settings 'core.settings'
Starting development server at http://127.0.0.1:8000/
Quit the server with CONTROL-C.
```
You can quit the Django server by presing `CTRL + C `.

Now, you will need to edit the Django project `settings.py` file by executing this command:
`nano core/settings.py`

*Also, you can use `vim` and if it's difficult, you can use [File Manager](https://www.hostinger.com/tutorials/how-to-use-hostinger-file-manager/ "File Manager") to edit the files.

You should find this line:
`ALLOWED_HOSTS = ['127.0.0.1']`

When you will find that line, change the value from `127.0.0.1` to `yourdomain.tld`. It should look like this:
`ALLOWED_HOSTS = ['yourdomain.tld']`

Don't forget to save the changes!

**replace u0123456789 and yourdomain.tld with your hosting username and your domain name.*

## Configuring Cloudflare tunnels

To properly connect the localhost:8000 to your domain name, you will need a solution which is called "tunneling". If you want to learn more about Cloudflare tunnels, you can read [this article](https://www.cloudflare.com/products/tunnel/ "this article").

To begin configuring Cloudflare tunnels, you need to be in the correct location:
`cd /home/u0123456789/domains/yourdomain.tld/public_html/Django_on_Shared_hosting_plan_Hostinger`

Then, you will need to give the files "cloudflared" and "hell" execution permissions:
`chmod +x cloudflared`
`chmod +x hell`

After that, you can authenticate `cloudflared` client by running this command:
`./cloudflared tunnel login`
In the output, there should be a link that you need to visit and sign in to your CloudFlare account. Don't forget to select your domain name and click **Authorize**.

Now, create the tunnel by executing this command:
`./cloudflared tunnel create name_of_tunnel`

From the output of the command, take note of the tunnel’s UUID and the path to your tunnel’s credentials file.

**replace u0123456789, yourdomain.tld and name_of_tunnel with your hosting username, your domain name and the name of the tunnel*

### Modify the credentials file

To make a correct connection with your Django application, it's needed to configure some rules. To do that, move the `config.yml ` file by executing this command:
`mv /home/u0123456789/domains/yourdomain.tld/public_html/Django_on_Shared_hosting_plan_Hostinger/config.yml /home/u0123456789/.cloudflared`

Edit the config.yml file by executing this command:
`nano /home/u0123456789/.cloudflared/config.yml`

You should change:
- `tunnel` value to your tunnel’s UUID
- '`credentials-file`' value to the path of your .json file which you got by creating a tunnel (usually, that file is in .cloudflared folder).
- `hostname` value to your domain name

Don't forget to save the changes!

To complete the configuration, you need to route the traffic from the domain to your application by executing this command(make sure that you are in this location `/home/u0123456789/domains/yourdomain.tld/public_html/Django_on_Shared_hosting_plan_Hostinger`):
`./cloudflared tunnel route dns name_of_tunnel yourdomain.tld`

**replace u0123456789, yourdomain.tld and name_of_tunnel with your hosting username, your domain name and the name of the tunnel*

## Last steps

In the shared hosting plan the Django application and `Cloudflared` client don't start up automatically, we need to force it by using [Cron jobs](https://support.hostinger.com/en/articles/1583465-how-to-set-up-a-cron-job-at-hostinger "Cron jobs").

Furthermore, the **shell_exec()** function is disabled by default from hPanel, so you need to enable that by deleting it from PHP settings. You need to follow [this guide](https://support.hostinger.com/en/articles/3212034-how-to-enable-disabled-php-functions "this guide") for that.

### Setting up the Cron jobs

For some reason our PHP scripts don't work properly when we execute them via the PHP option on Cron jobs, so we need to use the `curl` command for them to work correctly. To use a `curl` command, we need to create a subdomain on our website from which we would reach our PHP scripts. You can create the subdomain for your website with a custom folder by following [this guide](https://support.hostinger.com/en/articles/1583405-how-to-create-and-delete-subdomains "this guide"). **Don't forget to create A record for your subdomain in Cloudflare!**

![image](https://github.com/Laury8nas/Django_on_Shared_hosting_plan_Hostinger/assets/30197870/644caae9-281b-47e8-9db8-0160e4025c49)

After the subdomain is created, move the `check.php`, `check2.php` and `check3.php` files (from `/home/u0123456789/domains/yourdomain.tld/public_html/Django_on_Shared_hosting_plan_Hostinger`) to your created subdomain folder.

Before setting up Cron jobs, we need to make our PHP scripts fully working. You need to open `check.php`, `check2.php` and `check3.php`. In those PHP files, you should modify the paths in the commands, so they would match your username and domain name.

Now, we can set up 3 Cron Jobs by selecting the **Custom** type and entering these commands:
- 1 Cron job: `curl "https://subdomain.yourdomain.tld/check.php"`
- 2 Cron job: `curl "https://subdomain.yourdomain.tld/check2.php"`
- 3 Cron job: `curl "https://subdomain.yourdomain.tld/check3.php"`

![image](https://github.com/Laury8nas/Django_on_Shared_hosting_plan_Hostinger/assets/30197870/e9e24987-99ee-44a6-bd44-801faf975dad)


The first and second cron jobs should run every minute, however, the third cron job should be created after 5 minutes from the second one and it should be configured to run every 10 minutes. You should do this because the server kills all the processes after an hour of their creation, so they should be duplicated. The logic is that when one process is killed, the other will be already here to take the connection with Cloudflare (the whole client will not be killed as the other process is created with a delay of 5 minutes, so the killed process will respawn after one minute with a help of Cron job).

**Vuolia!** When the first and second cron job is started, your Django project should be up and running. My example project should show only HTML code because it needs some modification in the project files for the CSS and JS files to work.

![image](https://github.com/Laury8nas/Django_on_Shared_hosting_plan_Hostinger/assets/30197870/5d29a131-8b19-437a-94cc-ef3c042d4edd)

## Conclusions

This project was a great demonstration that the impossible can always become achievable. However, there are a few things to consider when thinking about whether this method is good and reliable:
- At this point, I was only able to duplicate the `cloudflared` client, so it won't experience downtime. However, the Django server still gets killed after 1 hour so every hour there are 1-2 minutes of downtime(till the cron job relaunches the Django server). For now, I don't have a solution that would let to run different Django server processes on the same port.
- The resource usage on NPROC(the total number of processes that are running in the background) for the Premium hosting plan is not acceptable because it's way over the limits(50+). This means you would need to have at least a Business plan or even better the Cloud Startup hosting plan, but that defeats the whole purpose because for the same price, you can get a decent VPS plan and host a Django application without any problems.
![image](https://github.com/Laury8nas/Django_on_Shared_hosting_plan_Hostinger/assets/30197870/1da6eb7b-e734-49a8-a54a-c9b1b8d1ba95)
- There are[ some security risks](https://www.csoonline.com/article/649000/attackers-use-cloudflare-tunnel-to-proxy-into-victim-networks.html " some security risks") if Cloudflare tunnels are not configured properly or you don't know what you're doing. So, if you are working with sensitive information, it's not a way to go.
- [Used Django project ](https://github.com/mymi14s/Django-Project-Starter-Template/tree/master "used project ")was based on Python 3.6, so if it would require a higher version there could be some problems because we cannot change the whole hosting server's Python version. There can be some workarounds with the `venv` module but I'm still not sure about that.
