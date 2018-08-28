/*Retorna o código do aluno, nome da disciplinas, e a maior nota de cada aluno.*/
SELECT aluno.aluno, disciplina.nome, MAX(nota.valor) FROM aluno 
INNER JOIN matricula ON aluno.aluno = matricula.aluno 
INNER JOIN disciplina ON disciplina.disciplina = matricula.disciplina LEFT OUTER JOIN nota ON nota.matricula = matricula.matricula 
GROUP BY aluno.aluno, disciplina.nome;

/*PARA A PÁGINA RESUMO PRECISO DESSAS INFORMACOES*/

/*Retorna a quantidade de alunos que estão matriculados na disciplina de ID = 1.*/
SELECT COUNT(aluno.aluno) AS total_alunos FROM aluno INNER JOIN matricula ON aluno.aluno = matricula.aluno 
INNER JOIN disciplina ON disciplina.disciplina = matricula.disciplina WHERE disciplina.disciplina = 1;

/*Retorna quantos alunos possuem nota >= a 6 (aprovados) na disciplina de ID = 1.*/
SELECT COUNT(aluno.aluno) AS alunos_aprovados FROM disciplina INNER JOIN nota ON disciplina.disciplina = nota.disciplina
INNER JOIN aluno ON aluno.aluno = nota.aluno WHERE nota.valor >= 6 AND disciplina.disciplina = 1;

/*Retorna quantos alunos possuem nota <= a 6 (reprovados) na disciplina de ID = 1.*/
SELECT COUNT(aluno.aluno) AS alunos_reprovados FROM disciplina INNER JOIN nota ON disciplina.disciplina = nota.disciplina
INNER JOIN aluno ON aluno.aluno = nota.aluno WHERE nota.valor <= 6 AND disciplina.disciplina = 1;
