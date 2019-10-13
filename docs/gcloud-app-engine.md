## Run on Google App Engine.

###1. Create Google App Engine.
1. Create a project in the GCP Console and make note of your project ID: https://cloud.google.com/resource-manager/docs/creating-managing-projects
2. Install the Google Cloud SDK: https://cloud.google.com/sdk/

###2. Change scripts section in our composer.json file.
    "scripts": {
        "[ $COMPOSER_DEV_MODE -eq 0 ] || auto-scripts": {
            "cache:clear": "symfony-cmd",
            "assets:install %PUBLIC_DIR%": "symfony-cmd"
        },
        "[ $COMPOSER_DEV_MODE -eq 0 ] || post-install-cmd": [
            "@auto-scripts"
        ],
        "[ $COMPOSER_DEV_MODE -eq 0 ] || post-update-cmd": [
            "@auto-scripts"
        ]
    },

**Note:** The composer scripts run on the Cloud Build server.

###3. Overwrite cache and log directories so they use tmp directory on production.
    class Kernel extends BaseKernel
    {
        //...
    
        public function getCacheDir()
        {
            if ($this->environment === 'prod') {
                return sys_get_temp_dir();
            }
            return $this->getProjectDir() . '/var/cache/' . $this->environment;
        }
    
        public function getLogDir()
        {
            if ($this->environment === 'prod') {
                return sys_get_temp_dir();
            }
            return $this->getProjectDir() . '/var/log';
        }
    
        // ...
    }
**Note:** This is required because App Engine's file system is **read-only**.
  
###4. Create and link Cloud Sql with Doctrine.  
1. Follow the instructions to set up a Cloud Sql Database: https://cloud.google.com/sql/docs/mysql/create-instance
2. Set up cloud_sql_proxy for your database: https://cloud.google.com/sql/docs/mysql/connect-admin-proxy?hl=de
3. Run proxy: `cloud_sql_proxy -instances=INSTANCE_CONNECTION_NAME=tcp:3306 &`
4. Create database schema with doctrine locally: `bin/console doctrine:schema:create`
5. Run doctrine migrations locally: `bin/console doctrine:migrations:migrate`
  
###5. Copy app.yaml definition.
1. Copy `app.yaml.dist` to `app.yaml`.
2. Change your app secret.
3. Modify your app.yaml file. Be sure to replace DB_PASSWORD and INSTANCE_CONNECTION_NAME with the values you created for your Cloud SQL instance.

###6. Set up Stackdriver Logging and Error Reporting.
Comming soon..

###7. Deploy
Run: `gcloud app deploy --project=[YOUR_PROJECT_ID]`
