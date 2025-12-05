pipeline {
    agent any

    stages {
        stage('Compilar y analizar') {
            steps {
                // 1. Compila
                sh 'mvn clean compile'
                
                // 2. Analiza con SonarQube
                withSonarQubeEnv('SonarCloud-ISTB') {
                    sh 'mvn sonar:sonar'
                }
            }
        }
    }
}
