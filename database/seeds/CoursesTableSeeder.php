<?php

use App\Judite\Models\Course;
use Illuminate\Database\Seeder;

class CoursesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        Course::create(['code' => 'H501N1', 'name' => 'Álgebra Linear EI', 'semester' => 1, 'year' => 1]);
        Course::create(['code' => 'H501N2', 'name' => 'Cálculo', 'semester' => 1, 'year' => 1]);
        Course::create(['code' => 'H501N5', 'name' => 'Elementos de Engenharia de Sistemas', 'semester' => 1, 'year' => 1]);
        Course::create(['code' => 'H501N6', 'name' => 'Laboratórios de Informática I', 'semester' => 1, 'year' => 1]);
        Course::create(['code' => 'H501N4', 'name' => 'Programação Funcional', 'semester' => 1, 'year' => 1]);
        Course::create(['code' => 'H501N3', 'name' => 'Tópicos de Matemática Discreta', 'semester' => 1, 'year' => 1]);
        Course::create(['code' => 'H502N2', 'name' => 'Análise', 'semester' => 2, 'year' => 1]);
        Course::create(['code' => 'H502N6', 'name' => 'Laboratórios de Informática II', 'semester' => 2, 'year' => 1]);
        Course::create(['code' => 'H502N4', 'name' => 'Lógica EI', 'semester' => 2, 'year' => 1]);
        Course::create(['code' => 'H502N5', 'name' => 'Programação Imperativa', 'semester' => 2, 'year' => 1]);
        Course::create(['code' => 'H502N1', 'name' => 'Sistemas de Computação', 'semester' => 2, 'year' => 1]);
        Course::create(['code' => 'H502N3', 'name' => 'Tópicos de Física Moderna', 'semester' => 2, 'year' => 1]);

        Course::create(['code' => 'H503N6', 'name' => 'Algoritmos e Complexidade', 'semester' => 1, 'year' => 2]);
        Course::create(['code' => 'H503N4', 'name' => 'Arquitetura de Computadores', 'semester' => 1, 'year' => 2]);
        Course::create(['code' => 'H503N5', 'name' => 'Comunicação de Dados', 'semester' => 1, 'year' => 2]);
        Course::create(['code' => 'H503N3', 'name' => 'Engenharia Económica', 'semester' => 1, 'year' => 2]);
        Course::create(['code' => 'H503N2', 'name' => 'Estatística Aplicada', 'semester' => 1, 'year' => 2]);
        Course::create(['code' => 'H503N1', 'name' => 'Introdução aos Sistemas Dinâmicos', 'semester' => 1, 'year' => 2]);
        Course::create(['code' => 'H504N5', 'name' => 'Cálculo de Programas', 'semester' => 2, 'year' => 2]);
        Course::create(['code' => 'H504N3', 'name' => 'Eletromagnetismo EE', 'semester' => 2, 'year' => 2]);
        Course::create(['code' => 'H504N6', 'name' => 'Laboratórios de Informática III', 'semester' => 2, 'year' => 2]);
        Course::create(['code' => 'H504N2', 'name' => 'Programação Orientada aos Objetos', 'semester' => 2, 'year' => 2]);
        Course::create(['code' => 'H504N1', 'name' => 'Sistemas Operativos', 'semester' => 2, 'year' => 2]);
        // Course::create(['code' => '', 'name' => 'Opção UMinho', 'semester' => 2, 'year' => 2]);

        Course::create(['code' => 'H505N1', 'name' => 'Bases de Dados', 'semester' => 1, 'year' => 3]);
        Course::create(['code' => 'H505N2', 'name' => 'Desenvolvimento de Sistemas de Software', 'semester' => 1, 'year' => 3]);
        Course::create(['code' => 'H505N6', 'name' => 'Métodos Numéricos e Otimização Não Linear', 'semester' => 1, 'year' => 3]);
        Course::create(['code' => 'H505N3', 'name' => 'Modelos Determinísticos de Investigação Operacional', 'semester' => 1, 'year' => 3]);
        Course::create(['code' => 'H505N5', 'name' => 'Redes de Computadores', 'semester' => 1, 'year' => 3]);
        Course::create(['code' => 'H505N4', 'name' => 'Sistemas Distribuídos', 'semester' => 1, 'year' => 3]);
        Course::create(['code' => 'H506N2', 'name' => 'Computação Gráfica', 'semester' => 2, 'year' => 3]);
        Course::create(['code' => 'H506N4', 'name' => 'Comunicações por Computador', 'semester' => 2, 'year' => 3]);
        Course::create(['code' => 'H506N6', 'name' => 'Laboratórios de Informática IV', 'semester' => 2, 'year' => 3]);
        Course::create(['code' => 'H506N3', 'name' => 'Modelos Estocásticos de Investigação Operacional', 'semester' => 2, 'year' => 3]);
        Course::create(['code' => 'H506N5', 'name' => 'Processamento de Linguagens', 'semester' => 2, 'year' => 3]);
        Course::create(['code' => 'H506N1', 'name' => 'Sistemas de Representação de Conhecimento e Raciocínio', 'semester' => 2, 'year' => 3]);
    }
}
