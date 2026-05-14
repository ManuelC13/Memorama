/**
 * Autores: Manuel Cupul y Raúl Batun
 * Proyecto: Memorama (Memosoft)
 * Descripción: Pipeline de integración continua para ejecutar pruebas unitarias con Jest y PHPUnit
 * Se ejecuta automáticamente al hacer push a la rama main.
 */

pipeline {

    agent any

    environment {
        NODE_DIR     = "."
        NOTIFY_EMAIL = "manuelenriquemaycupul@gmail.com"
    }

    triggers {
        githubPush()
    }

    stages {

        // 1. Instalar dependencias
        stage("Instalar dependencias") {
            parallel {

                stage("Node.js") {
                    steps {
                        dir("${NODE_DIR}") {
                            sh "npm ci"
                        }
                    }
                }

                stage("PHP") {
                    steps {
                        dir("${NODE_DIR}") {
                            sh "composer install --no-interaction"
                        }
                    }
                }

            }
        }

        // 2. Ejecutar pruebas
        stage("Ejecutar pruebas") {
            parallel {

                stage("Jest (JS)") {
                    steps {
                        dir("${NODE_DIR}") {
                            sh "npm test -- --ci --forceExit"
                        }
                    }
                }

                stage("PHPUnit (PHP)") {
                    steps {
                        dir("${NODE_DIR}") {
                            sh "vendor/bin/phpunit tests/"
                        }
                    }
                }

            }
        }

    }

    post {

        success {
            echo "Todas las pruebas pasaron correctamente."
        }

        failure {
            echo "Una o más pruebas fallaron. Enviando notificación..."
            mail(
                to:      "${NOTIFY_EMAIL}",
                subject: "[Memosoft] Pipeline fallido — ${env.JOB_NAME} #${env.BUILD_NUMBER}",
                body:    """
Hola,

El pipeline de Memosoft falló en el build #${env.BUILD_NUMBER}.

Rama:   ${env.GIT_BRANCH}
Commit: ${env.GIT_COMMIT}

Revisa los logs para ver qué prueba falló:
${env.BUILD_URL}

— Jenkins / Memosoft CI
                """.stripIndent()
            )
        }

        always {
            dir("${NODE_DIR}") {
                sh "rm -rf node_modules vendor"
            }
        }

    }
}