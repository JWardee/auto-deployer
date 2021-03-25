## Install
- Ensure Ansible is installed.
- `artisan migrate --seed`
- `artisan autodeployer:install`
- Ensure that the public key generated in `storage/app/ansible_rsa.pub` has access to your server that will receive the deployment.
- Go to `/nova` and add your project, use the default credentials: `test@test.com` and `test` as the password. Add the generated deploy key to your git repo.
- *Optionally* Add `/api/deploy` to your git webhooks (Payload only tested against GitHub)
- Either select `deploy` from the projects dropdown or to a push to your repo. On first deploy the project will be cloned. On subsequent pushes a pull will be done.

## TODO
- On webhook receive deploy from a job
- On Deploy from Nova action fire the deploy as a job
- Show output of deploys inside the activity logger
