// Jenkinsfile с исправленным этапом тестирования

pipeline {
    agent any

    environment {
        SWARM_STACK_NAME = 'app'
        FRONTEND_URL = 'http://192.168.0.1:8080'
    }

    stages {
        stage('Checkout') {
            steps {
                checkout scm
            }
        }

        stage('Build Docker Images') {
            steps {
                script {
                    sh "docker build -f php.Dockerfile -t app-web:latest ."
                    sh "docker build -f mysql.Dockerfile -t app-db:latest ."
                }
            }
        }

        stage('Deploy to Docker Swarm') {
            steps {
                script {
                    sh '''
                        if ! docker info | grep -q "Swarm: active"; then
                            docker swarm init || true
                        fi
                    '''
                    sh "docker stack deploy --with-registry-auth -c docker-compose.yaml ${SWARM_STACK_NAME}"
                }
            }
        }

        stage('Run Automated Tests') {
            steps {
                script {
                    echo 'Ожидание запуска сервисов...'
                    sleep time: 30, unit: 'SECONDS'

                    echo 'Проверка доступности фронтенда и наличия данных из БД...'
                    
                    // Эта команда упадет, если на странице не будет слова "Наименование",
                    // что произойдет, если база данных недоступна.
                    sh "curl -fsS ${FRONTEND_URL} | grep 'Наименование'"
                }
            }
        }
    }

    post {
        success {
            echo 'Все этапы успешно завершены'
        }
        failure {
            echo 'Ошибка в одном из этапов. Проверь логи выше.'
        }
        always {
            cleanWs()
        }
    }
}
