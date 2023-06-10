(1º) ROTA PUBLICA POST DE CRIAÇÃO DE USUARIOS http://127.0.0.1:8000/api/users
TEM QUE PASSA OS SEGUINTES CAMPOS NO JSON EXEMPLO:
{
  "email":"orangelabs",
  "password":"123"
}


(2º) ROTA PUBLICA POST LOGIN DE USUARIOS http://127.0.0.1:8000/api/login_check
TEM QUE FAZER LOGIN POR QUE NÃO TERAR ACESSO AS PROXIMAS ROTAS, APARTIR DO TOKEN GERADO VOCÊ TERAR ACESSO TOTAL AS ROTAS.
EXEMPLO DE LOGIN PASSANDO O JSON: 
{
  "email":"orangelabs",
  "password":"123"
}

RETORNARA ESSE MODELO:
{
	"token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJpYXQiOjE2ODY0MDk3OTksImV4cCI6MTY4NjQxMzM5OSwicm9sZXMiOlsiUk9MRV9VU0VSIl0sImVtYWlsIjoianBUZXN0ZTEifQ.AxtHnfHPtFKBXJ5KFCEjNLMWOqRUnd7e3xgMDeBePVSLXnTxxY3mW6WGG9ditCnNCkIfQX5zWR8cPshO_PwexscO6Rj8P_o5XSwP411RmDcvhgLcsyydUyXaYycSTO3QNV6jSZHLXMZOxPqjCpu0K87m_2nVARFliYKghTtSrNkS-Qiui7d-_kmdXDzE_3BznUgMOzbJ69viKeWslP3JkdUdOxlPtHs1Ok72ndQ3rRBMXyEICdu9Qfi7oBwwcssZbw_k53f6Ap9jCkC4rnIMxU-mX0iMWr9qqaQxf-kr79q1MIFUzFIZ7Eb7I7_ttbJQztFqt_YKFiK8bqrM7Df1Ng"
}


(3º) ROTA PRIVADA GET LISTA TODAS AS TAREFAS E SUBTAREFAS ONDE COMTEM UM SISTEMA DE PAGINAÇÃO http://127.0.0.1:8000/api/tasks/sub/1/5/0  
NO PRIMEIRO PARÂMETRO http://127.0.0.1:8000/api/tasks/sub/1/ REPRESENTA O NUMERO DE PAGINAS 
NO SEGUNDO PARÂMETRO  http://127.0.0.1:8000/api/tasks/sub/1/5/ REPRESENTA A QUANTIDADE DE REGISTRO POR PAGINAS 
NO TERCEIRO PARÊMETRO http://127.0.0.1:8000/api/tasks/sub/1/5/0 REPRESENTA 0 PARA AS TAREFAS NÃO CONCLUIDAS 1 PARA AS TAREFAS CONCLUIDAS NESSE ULTIMO PARÊMETRO SO RECEBER ESSE DOIS VALORES OS DEMAIS SÃO IGNORADOS PELO SISTEMAS DE VALIDAÇÃO 


(4º) ROTA PRIVADA POST http://127.0.0.1:8000/api/tasks ONDE TEM QUE ESTA AUTENTICADO PARA FAZER USO, ONDE PODERAR CRIAR AS TAREFAS E SUBTAREFAS
EXEMPLO JSON:
{
  "title": "Orangelabs",
  "subTasks": [
    {
      "title": "Criar Tema da Reunião "
    },
		{
      "title": "Borda Estrategia de Vendas "
    }
		
  ]
}
ALTOMATICAMETE JA INCLUE NA CRIAÇÃO A DATA DE CRIÇÃO E A DATA DE ATAUALIZAÇÃO 
EXEPLO DO RETORNO DO JSON:
{
	"message": "task created successfully!",
	"data": {
		"id": 23,
		"title": "Orangelabs",
		"TaskFinished": 0,
		"createdAt": "2023-06-10 12:11:17",
		"updateAt": "2023-06-10 12:11:17",
		"subTasks": [
			{
				"id": 47,
				"title": "Criar Tema da Reunião"
			},
			{
				"id": 48,
				"title": "Borda Estrategia de Vendas"
			}
		]
	}
}

(5º) ROTA PRIVADA POST http://127.0.0.1:8000/api/tasks/2/subtasks ON TEM QUE ESTA AUTENTICADO PARA FAZER USO, ONDE PODERAR CRIAR AS SUBTAREFAS 
EXEMPLO JSON:
{
  "title": "Cria Tarefa"
}
http://127.0.0.1:8000/api/tasks/2/subtasks  ONDE O NUMERO DOIS TEM QUE SER O ID DA TAREFA QUE VC CRIO ONDE SERA ADICIONADO NA LISTA DAS SUBTASKS 
EXEMPLO:

{
	"message": "task created successfully!",
	"data": {
		"id": 23,
		"title": "Orangelabs",
		"TaskFinished": 0,
		"createdAt": "2023-06-10 12:11:17",
		"updateAt": "2023-06-10 12:11:17",
		"subTasks": [
			{
				"id": 47,
				"title": "Criar Tema da Reunião"
			},
			{
				"id": 48,
				"title": "Borda Estrategia de Vendas"
			},
            {   "id": 49,
                "title": "Cria Tarefa"
            }
		]
	}
}

(6º) ROTA PRIVADA PUT DE TAREFAS  http://127.0.0.1:8000/api/tasks/2 ONDE PASSA ID  DA TERFA QUE VAI SER MODIFICADA, ONDE TAMBEM VAI SER ALTERADO O VALOR ONDE DESCRVER SE FOI CONCUIDA OU NÃO, EXEMPLO:
{
	"title":"TUDO OK",
	"TaskFinished":1
}

(7º) ROTA PRIVADA DELETE TAREFAS http://127.0.0.1:8000/api/tasks/1 ONDE PASSA ID  DA TERFA QUE VAI SER DELETADA

(8º) ROTA PRIVADA DELETE SUBTAREFAS http://127.0.0.1:8000/api/tasks/2/subtasks/41  ONDE NO PRIMEIRO PARÂMETRO REPRESENTA A TASK DE REFERENCIA E NO SEGUNDA PARÂMETRO ONDE VAI SE ORIGINAR O DELETE EM SE QUE TEM QUE PASSAR O ID DA SUBTAREFA EXEMPLO EM JSON:

 {
	"message": "task created successfully!",
	"data": {
		"id": 23, OBS////////// ID TAREFA
		"title": "Orangelabs",
		"TaskFinished": 0,
		"createdAt": "2023-06-10 12:11:17",
		"updateAt": "2023-06-10 12:11:17",
		"subTasks": [
			{
				"id": 47, OBS////////// ID SUBTAREFA
				"title": "Criar Tema da Reunião"
			},
			{
				"id": 48,
				"title": "Borda Estrategia de Vendas"
			},
            {   "id": 49,
                "title": "Cria Tarefa"
            }
		]
	}
}

NESSA ROTA SERA DELETADO SO A SUBTAREFA