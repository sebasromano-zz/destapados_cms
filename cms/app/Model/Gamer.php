<?php
class Gamer extends AppModel {

	public $name = 'Gamer';
	public $displayField = 'name';
	public $recursive = -1;

	public $validate = array(
		'email' => array( 'required' => array('rule' => 'notEmpty')),
	);

	public $virtualFields = array(
		'name' => 'CONCAT(Gamer.first_name, " ", Gamer.last_name)'
	);

	public $hasMany = array('Game', 'Answer');

	// actualiza el puntaje del usuario
	public function updateScore( $gamer_id){

		if( !empty( $gamer_id)){

			$options = array(
				'fields' => array( 'COUNT(*) as counter', 'SUM(score) as score'),
				'conditions' => array(
					'Answer.gamer_id' => $gamer_id
					),
				'group' => array('Answer.gamer_id')
				);

			$result = $this->Answer->find('all', $options);

			$score = 0;
			$levels = (isset($result[0][0]['counter']))?$result[0][0]['counter']:0;
			$totalScore = (isset($result[0][0]['score']))?$result[0][0]['score']:0;

			if( $totalScore > $levels){
				$score = ceil( $totalScore / $levels);
			}

			$dataToUpdate = array(
				'id' => $gamer_id,
				'games' => $totalScore, // por ahora almaceno el puntaje total
				'levels' => $levels,
				'score' => $score // este uso para el ranking
				);

			$this->save( $dataToUpdate);
		}

	}

}