<?php
App::uses('AppController', 'Controller');

class ApiController extends AppController {

	public $name = 'Api';
	public $paginate = array();
	public $uses = array('Gamer','Question');
	public $keyForHash = '9823@@@sdfsfdf923_111';

	public function beforeFilter() {
		parent::beforeFilter();
		header('Content-Type: application/json');
		$this->autoRender = false;
		$this->autoLayout = false;
		$this->Auth->allow(array('get_token', 'get_question', 'get_questions', 'get_gamers', 'get_score', 'user_suggest', 'get_ranking', 'test_api'));
	}


	public function get_token(){

		// busco la existencia del gamer
		if( isset($this->params->query['user_info']['id']) ){

			$options = array(
				'conditions' => array(
					'Gamer.fb_id' =>  $this->params->query['user_info']['id']
					)
				);

			$gamer = $this->Gamer->find('first', $options);

			if( $gamer){

				if( $gamer['Gamer']['active'] == 1){
					// si existe retorno la información
					$returnJSON = array(
						'error' => 'none',
						'user_id' => $gamer['Gamer']['id'],
						//'token' => sha1( $gamer['Gamer']['id'] . $this->keyForHash),
						'token' => $gamer['Gamer']['id'],
						'userInfo' => array(
							'fb_id' => $gamer['Gamer']['fb_id'],
							'first_name' => $gamer['Gamer']['first_name'],
							'last_name' => $gamer['Gamer']['last_name'],
							'email' => $gamer['Gamer']['email'],
							'timezone' => $gamer['Gamer']['timezone'],
							'locale' => $gamer['Gamer']['locale'],
							'gender' => $gamer['Gamer']['gender']
							)
						);

				}else{
					// por alguna razon el jugador está inactivo
					$returnJSON = array(
						'error' => 'user-inactive',
						);
				}

			}else{

				// usuario nuevo, lo creo
				$data = array(
					'fb_id' => $this->params->query['user_info']['id'],
					'first_name' => $this->params->query['user_info']['first_name'],
					'last_name' => $this->params->query['user_info']['last_name'],
					'email' => $this->params->query['user_info']['email'],
					'timezone' => $this->params->query['user_info']['timezone'],
					'locale' => $this->params->query['user_info']['locale'],
					'gender' => $this->params->query['user_info']['gender'],
					'active' => 1,
					'games' => 0,
					'levels' => 0,
					'score' => 0
					);
				$this->Gamer->create( $data);

				if( $this->Gamer->save($data) ){
					// retorno datos del usuario creado
					$returnJSON = array(
						'error' => 'none',
						'user_id' => $this->Gamer->getID(),
						// 'token' => sha1( $this->Gamer->getID() . $this->keyForHash),
						'token' => $this->Gamer->getID(),
						'userInfo' => $data
						);
				}else{
					$returnJSON = array(
						'error' => 'no-user-created',
						);
				}
			}

		}else{
			$returnJSON = array(
				'error' => 'no-data',
				);
		}
		die( json_encode( $returnJSON) );

	}

	public function get_question(){

		//CakeLog::write(LOG_ERR, 'get_question');
		//CakeLog::write(LOG_ERR, print_r($this->params, true) );
		//historyQuestions

		// chequeo existencia de datos y validez del token (para otra version, por ahora solo miro el token --recordar para v4--)
		if( isset($this->params->query['token']) && isset($this->params->query['category_id'])){

			$cat_id = $this->params->query['category_id'];
			$token = $this->params->query['token'];

			// busco ultimas preguntas realizadas al usuarios para exceptuarlas
			$options = array(
				'fields' => array( 'Answer.question_id', 'Answer.question_id'),
				'limit' => 50,
				'conditions' => array(
						'Answer.gamer_id' => $token,
						'Answer.category_id' => $cat_id
					),
				'group' => 'Answer.question_id'
				);

			$historyQuestions = $this->Question->Answer->find('list', $options);

			
			// busco proxima pregunta al azar
			$options = array(
				'conditions' => array(
					'Question.category_id' => $cat_id,
					'Question.active' => 1
					),
				'order' => 'RAND()'
				);

			//CakeLog::write(LOG_ERR, 'get_question_history');
			//CakeLog::write(LOG_ERR, print_r($historyQuestions, true) );


			if( $historyQuestions){
				$options['conditions']['Question.id NOT'] = $historyQuestions;
			}

			$question = $this->Question->find('first', $options);

			if( $question){			

				// creo un juego
				
				// guardo la pregunta realizada (así no repito y por ahora son los levels -- no hay games--)
				$levelToken = time().'-'.$token.'-'.$cat_id;
				$data = array(
					'gamer_id' => $token,
					'token' => $levelToken,
					'category_id' => $cat_id,
					'question_id' => $question['Question']['id'],
					'answer' => 0,
					'score' => 0
					);
				$this->Question->Answer->create( $data);
				$this->Question->Answer->save();
				// -----------------------------------------------------------------------------------------

				$returnJSON = array(
					'error' => 'none',
					'question' => $question['Question']['title'],
					'question_id' => $question['Question']['id'],
					'question_source' => $question['Question']['source'],
					//'time_start' => time(),
					'time_start' => $levelToken,
					'answers' => array(
						1 => $question['Question']['answer_1'],
						2 => $question['Question']['answer_2'],
						3 => $question['Question']['answer_3'],
						4 => $question['Question']['answer_4'],
						)
					);


			}else{
				// recordar que podría pasar mensakes de error
				$returnJSON = array(
						'error' => 'no-question',
						);				
			}

		}else{
			// recordar que podría pasar mensakes de error
			$returnJSON = array(
					'error' => 'no-data',
					);
		}

		die( json_encode( $returnJSON) );

	}

	public function get_score(){

		//CakeLog::write(LOG_ERR, 'score');
		//CakeLog::write(LOG_ERR, print_r($this->params, true) );

		// debería chequear token pero la tomo como válida por ahora
		if( isset($this->params->query['start_time']) && isset($this->params->query['answer_id']) && isset($this->params->query['question_id']) && isset($this->params->query['score']) ){

			$token = $this->params->query['start_time'];

			// información de la pregunta
			$question = $this->Question->read(null, $this->params->query['question_id']);

			// información de la respuesta abierta
			$answer = $this->Question->Answer->findByToken( $token);

			if( $question && $answer){

				if( $this->params->query['answer_id'] == $question['Question']['answer_ok'] ){
					// correcta
					$returnJSON = array(
						'error' => 'none',
						'status' => 'correct',
						'answer_ok' => $question['Question']['answer_ok']
						);
					$score = $this->params->query['score'];
				}else{
					// incorrecta
					$returnJSON = array(
						'error' => 'none',
						'status' => 'incorrect',
						'answer_ok' => $question['Question']['answer_ok']
						);
					$score = 0;
				}

				// actualizo answer
				$updateData = array(
					'id' => $answer['Answer']['id'],
					'answer' => $this->params->query['answer_id'],
					'score' => $score
					);
				$this->Question->Answer->save( $updateData);

				// actualiza score del usuario
				$this->Gamer->updateScore( $answer['Answer']['gamer_id']);

			}else{
				$returnJSON = array(
					'error' => 'no-question'
					);
			}

		}else{
			$returnJSON = array(
				'error' => 'no-data'
				);
		}

		//CakeLog::write(LOG_ERR, 'score_json');
		//CakeLog::write(LOG_ERR, print_r( $returnJSON, true) );

		die( json_encode( $returnJSON) );

	}

	public function get_ranking(){

		//CakeLog::write(LOG_ERR, 'score');
		//CakeLog::write(LOG_ERR, print_r($this->params, true) );

		if( isset($this->params->query['token']) ){

			$gamer_id = $this->params->query['token'];

			// busco los 10 usuarios con más puntos
			$options = array(
				'limit' => 100,
				'order' => 'Gamer.score DESC',
				'conditions' => array(
					'Gamer.active' => 1
					)
				);

			$gamers = $this->Gamer->find('all', $options);

			if( $gamers){

				$ranking = array();
				$gamerInRanking = 0;
				$i = 1;
				foreach( $gamers as $gamer){

					$me = false;
					if( $gamer['Gamer']['id'] == $gamer_id){
						$me = true;
						$gamerInRanking = $i; // si esta en los primeros 100 tengo el dato
					}

					// solo tomo los primeros 11
					if( $i < 11 ){
						$ranking[ $i] = array(
							'name' => $gamer['Gamer']['first_name'].' '.substr( $gamer['Gamer']['last_name'], 0, 1).'.',
							'score' => $gamer['Gamer']['score'],
							'pos' => $i,
							'me' => $me
							);
					}
					$i++;
				}

				// si el usuario no está en el ranking lo agrego al final
				if( $gamerInRanking == 0){
					// busco datos del usuario
					$gamerActual = $this->Gamer->read(null, $gamer_id);
					if( $gamerActual){
						$ranking[ $i] = array(
								'name' => $gamerActual['Gamer']['first_name'].' '.substr( $gamer['Gamer']['last_name'], 0, 1).'.',
								'score' => $gamerActual['Gamer']['score'],
								'pos' => '100+',
								'me' => true
								);
					}
				}

				// ------------------------------------------------------

				$returnJSON = array(
					'error' => 'none',
					'rows' => $ranking
					);

			}else{
				$returnJSON = array(
					'error' => 'no-data'
					);
			}

		}else{
			$returnJSON = array(
				'error' => 'no-data'
				);
		}

		die( json_encode( $returnJSON) );

	}


	public function user_suggest(){

		//CakeLog::write(LOG_ERR, 'suggest');
		//CakeLog::write(LOG_ERR, print_r($this->params, true) );

		if( isset($this->params->query['token']) ){

			$gamer_id = $this->params->query['token'];

			//if( isset($this->params->query['test']) && isset($this->params->query['answer_ok']) && isset($this->params->query['answer-1']) && isset($this->params->query['answer-2']) && isset($this->params->query['answer-3']) && isset($this->params->query['source']) ){
			if( isset($this->params->pass[0])) parse_str( $this->params->pass[0], $vars);

			//CakeLog::write(LOG_ERR, 'suggest_vars');
			//CakeLog::write(LOG_ERR, print_r($vars, true) );

			if( isset($vars['question']) && isset($vars['answer-ok']) && isset($vars['answer-1']) && isset($vars['answer-2']) && isset($vars['answer-3']) && isset($vars['source']) ){
				
				/*$data = array(
					'gamer_id' => $gamer_id,
					'category_id' => $this->params->query['test'], // cambiar a nombre mas elegante
					'source' => $this->params->query['source'],
					'answer_1' => $this->params->query['answer_ok'],
					'answer_2' => $this->params->query['answer-1'],
					'answer_3' => $this->params->query['answer-2'],
					'answer_4' => $this->params->query['answer-3'],
					'answer_ok' => 1, // siempre es 1 para los sugeridos
					'active' => 0,
					'suggested' => 1
					);*/

				$data = array(
					'gamer_id' => $gamer_id,
					'category_id' => $vars['test'], // cambiar a nombre mas elegante
					'source' => $vars['source'],
					'title' => $vars['question'],
					'answer_1' => $vars['answer-ok'],
					'answer_2' => $vars['answer-1'],
					'answer_3' => $vars['answer-2'],
					'answer_4' => $vars['answer-3'],
					'answer_ok' => 1, // siempre es 1 para los sugeridos
					'active' => 0,
					'suggested' => 1
					);

				$this->Question->create( $data);

				if( $this->Question->save($data)){
					// sugerida con éxito
					$returnJSON = array(
						'error' => 'none',
						'validation_error' => 'none',
						'validation_message' => '',
						);

				}else{

					$returnJSON = array(
						'error' => 'none',
						'validation_error' => 'error',
						'validation_message' => 'Debes completar todos los campos',
						);
				}

				

			}else{
				$returnJSON = array(
					'error' => 'none',
					'validation_error' => 'error',
					'validation_message' => 'Debes completar todos los campos',
					);
			}

		}

		die( json_encode( $returnJSON) );

	}

	public function get_questions(){

		header('Content-Type: application/json');

		$returnJSON = array(
			'error' => 'no-data'
			);		

		$questions = $this->Question->find( 'all');
		if( $questions){

			$returnJSON = array(
				'error' => 'none'
				);
			$i = 0;
			$returnData = array();
			foreach ($questions as $question) {
				$returnData[$i] = $question['Question'];
				$i++;
			}

			$returnJSON['data'] = $returnData;
		}

		die( json_encode( $returnJSON) );

	}

	public function get_gamers(){

		header('Content-Type: application/json');

		$returnJSON = array(
			'error' => 'no-data'
			);		

		$gamers = $this->Gamer->find( 'all');
		if( $gamers){

			$returnJSON = array(
				'error' => 'none'
				);
			$i = 0;
			$returnData = array();
			foreach ($gamers as $gamer) {
				unset($gamer['Gamer']['email']);
				$returnData[$i] = $gamer['Gamer'];
				$i++;
			}

			$returnJSON['data'] = $returnData;
		}

		die( json_encode( $returnJSON) );

	}

	public function test_api(){
		$this->Gamer->updateScore(3);
	}



}