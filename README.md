# Django installation on Hostinger shared hosting plan
In this guide I will show you how I deployed the Django application on Hostinger shared hosting plan. Keep in mind, that this guide is expermental and it's not a reliable solution to host a Django application. Also, be aware that by following my guide you might violate [Hostinger's Terms of Service](https://www.hostinger.com/legal/universal-terms-of-service-agreement "Hostinger's Terms of Service"). **Everything you do is your responsibility!**

## Preparation

Before going further, make sure to:

- Check if Python3 is installed on your shared hosting server by running this command via SSH: `python3` (Your terminal cursor should change to these signs `>>>` )
- Connect your domain to Cloudflare by folowing[ this guide](https://support.hostinger.com/en/articles/4741545-how-to-use-cloudflare " this guide").
- Check if domain's A/CNAME/AAAA record (for the root value) in Cloudflare's DNS zone is deleted.
