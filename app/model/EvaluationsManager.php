<?php

namespace App\Model;

use Nette,
     Nette\Database\Context;

class EvaluationsManager extends DatabaseManager {

    use Nette\SmartObject;

    const
	TABLE_NAME = 'evaluation',
	COLUMN_ID = 'id',
	COLUMN_NEWS_ID = 'news_id',
	COLUMN_USERS_ID = 'users_id',
	COLUMN_INCH_UP = 'inch_up',
        COLUMN_INCH_DOWN = 'inch_down',
        COLUMN_EVALUATION = 'evaluation',
	COLUMN_CREATE_DATE = 'created_at';    

    private function getPublicEvaluation(){
        return $this->database->table(self::TABLE_NAME);
    }

    public function getPublicEvaluationNewsId($newsId){
        return $this->getPublicEvaluation()->where(self::COLUMN_NEWS_ID, $newsId);
    }

    public function getNameColumns(){
        return $this->database->getConnection()->GetSupplementalDriver();
    }

    public function getPublicEvaluationInchNewsId($newsId){
        var_dump($newsId);
    }

    public function setPublicEvaluationDelete($evaluation_id){
        return $this->database->table(self::TABLE_NAME)->where(self::TABLE_ID, $evaluation_id)->delete();
    }

    public function setPublicEvaluation($news_id,$evaluation_data,$user_id){
        $generateTable = $this->generateTableEvaluation($news_id,$evaluation_data,$user_id);
        $row = $this->getPublicEvaluation()->insert($generateTable);
        return $row;
    }


    private function generateTableEvaluation($news_id,$evaluation_data,$user_id){
        $evaluationTable = [
            self::COLUMN_NEWS_ID  => (int)$news_id,
            self::COLUMN_USERS_ID => (int)$user_id
        ];
            if($evaluation_data == 'true'){
                $evaluationTable[self::COLUMN_INCH_UP] = 1;
            }elseif($evaluation_data == 'false'){
                $evaluationTable[self::COLUMN_INCH_DOWN] = 1;
            }
        return $evaluationTable;
    }


}