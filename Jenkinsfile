pipeline {
    agent any

    environment {
        EMAIL_TO = 'srengty@gmail.com'
    }

    triggers {
        pollSCM('H/5 * * * *')
    }

    stages {
        stage('Checkout') {
            steps {
                git branch: 'main', url: 'https://github.com/Sithya/terrain-booking-system.git'
            }
        }

        stage('Build & Test') {
            steps {
                sh 'php artisan test --env=testing'
            }
        }

        stage('Deploy') {
            steps {
                sh 'ansible-playbook deploy.yml'
            }
        }
    }

    post {
        failure {
            emailext (
                subject: "❌ Build Failed: ${env.JOB_NAME} #${env.BUILD_NUMBER}",
                body: "Build failed: ${env.BUILD_URL}",
                recipientProviders: [developers(), culprits()],
                to: "${EMAIL_TO}"
            )
        }
    }
}
