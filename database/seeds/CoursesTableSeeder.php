<?php

use App\Judite\Models\Course;
use Illuminate\Database\Seeder;

class CoursesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Course::create(['name' => 'Álgebra Linear EI', 'semester' => 1, 'year' => 1]);
        Course::create(['name' => 'Cálculo', 'semester' => 1, 'year' => 1]);
        Course::create(['name' => 'Elementos de Engenharia de Sistemas', 'semester' => 1, 'year' => 1]);
        Course::create(['name' => 'Laboratórios de Informática I', 'semester' => 1, 'year' => 1]);
        Course::create(['name' => 'Programação Funcional', 'semester' => 1, 'year' => 1]);
        Course::create(['name' => 'Tópicos de Matemática Discreta', 'semester' => 1, 'year' => 1]);
        Course::create(['name' => 'Análise', 'semester' => 2, 'year' => 1]);
        Course::create(['name' => 'Laboratórios de Informática II', 'semester' => 2, 'year' => 1]);
        Course::create(['name' => 'Lógica EI', 'semester' => 2, 'year' => 1]);
        Course::create(['name' => 'Programação Imperativa', 'semester' => 2, 'year' => 1]);
        Course::create(['name' => 'Sistemas de Computação', 'semester' => 2, 'year' => 1]);
        Course::create(['name' => 'Tópicos de Física Moderna', 'semester' => 2, 'year' => 1]);

        Course::create(['name' => 'Algoritmos e Complexidade', 'semester' => 1, 'year' => 2]);
        Course::create(['name' => 'Arquitetura de Computadores', 'semester' => 1, 'year' => 2]);
        Course::create(['name' => 'Comunicação de Dados', 'semester' => 1, 'year' => 2]);
        Course::create(['name' => 'Engenharia Económica', 'semester' => 1, 'year' => 2]);
        Course::create(['name' => 'Estatística Aplicada', 'semester' => 1, 'year' => 2]);
        Course::create(['name' => 'Introdução aos Sistemas Dinâmicos', 'semester' => 1, 'year' => 2]);
        Course::create(['name' => 'Cálculo de Programas', 'semester' => 2, 'year' => 2]);
        Course::create(['name' => 'Eletromagnetismo EE', 'semester' => 2, 'year' => 2]);
        Course::create(['name' => 'Laboratórios de Informática III', 'semester' => 2, 'year' => 2]);
        Course::create(['name' => 'Programação Orientada aos Objetos', 'semester' => 2, 'year' => 2]);
        Course::create(['name' => 'Sistemas Operativos', 'semester' => 2, 'year' => 2]);
        // Course::create(['name' => 'Opção UMinho', 'semester' => 2, 'year' => 2]);

        Course::create(['name' => 'Bases de Dados', 'semester' => 1, 'year' => 3]);
        Course::create(['name' => 'Desenvolvimento de Sistemas de Software', 'semester' => 1, 'year' => 3]);
        Course::create(['name' => 'Métodos Numéricos e Otimização Não Linear', 'semester' => 1, 'year' => 3]);
        Course::create(['name' => 'Modelos Determinísticos de Investigação Operacional', 'semester' => 1, 'year' => 3]);
        Course::create(['name' => 'Redes de Computadores', 'semester' => 1, 'year' => 3]);
        Course::create(['name' => 'Sistemas Distribuídos', 'semester' => 1, 'year' => 3]);
        Course::create(['name' => 'Computação Gráfica', 'semester' => 2, 'year' => 3]);
        Course::create(['name' => 'Comunicações por Computador', 'semester' => 2, 'year' => 3]);
        Course::create(['name' => 'Laboratórios de Informática IV', 'semester' => 2, 'year' => 3]);
        Course::create(['name' => 'Modelos Estocásticos de Investigação Operacional', 'semester' => 2, 'year' => 3]);
        Course::create(['name' => 'Processamento de Linguagens', 'semester' => 2, 'year' => 3]);
        Course::create(['name' => 'Sistemas de Representação de Conhecimento e Raciocínio', 'semester' => 2, 'year' => 3]);
    }
}
