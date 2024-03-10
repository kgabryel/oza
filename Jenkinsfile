pipeline {
    agent any

    stages {
        stage('PHP install') {
            steps {
                withCredentials([file(credentialsId: 'OZA_env', variable: 'env')]) {
                    sh 'cp $env src/.env'
                }
                sh 'cd src && composer install --no-dev'
                sh 'cd src && php bin/console doctrine:migrations:migrate -n'
                sh 'cd src && php bin/console doctrine:fixtures:load --append'
            }
        }

        stage('JS install') {
            steps {
                sh 'cd src && yarn install'
                sh 'cd src && yarn run build'
            }
        }

        stage('deploy') {
            steps {
                sh 'sudo cp -r -f /var/www/html/oza/var/files src/var/files'
                sh 'sudo cp -r -f /var/www/html/oza/var/sessions src/var/sessions'
                sh 'cd src && rm -rf node_modules'
                sh 'sudo rm -rf /var/www/html/oza'
                sh 'sudo cp -r -f src /var/www/html/oza'
                sh 'sudo chmod 755 -R /var/www/html/oza'
                sh 'sudo chown www-data -R /var/www/html/oza'
            }
        }
    }
    post {
        always {
           cleanWs()
        }
    }
}
