pipeline {
    agent any

    stages {
        stage('Análisis con SonarQube') {
            steps {
                withSonarQubeEnv('SonarCloud-ISTB') {
                    sh 'mvn clean compile sonar:sonar'
                }
            }
        }
    }
}
