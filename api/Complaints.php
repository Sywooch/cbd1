<?php

namespace api;

use api\base\ActiveRecord;
use Yii;

/**
 * This is the model class for table "complaints".
 *
 * @property integer $id
 * @property integer $author_id
 * @property string $title
 * @property string $description
 * @property string $date
 * @property integer $dateSubmitted
 * @property integer $dateAnswered
 * @property integer $dateEscalated
 * @property integer $dateDecision
 * @property integer $dateCancelled
 * @property string $status
 * @property string $type
 * @property string $resolution
 * @property string $resolutionType
 * @property integer $satisfied
 * @property string $decision
 * @property string $cancellationReason
 * @property string $relatedLot
 * @property string $tendererAction
 * @property string $tendererActionDate
 * @property integer $created_at
 * @property integer $updated_at
 */
class Complaints extends ActiveRecord
{

    public function behaviors()
    {
        return parent::behaviors();
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'api_complaints';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [
                [
                    'id',
                    'author_id',
                    'title',
                    'description',
                    'status',
                    'type',
                    'resolutionType',
                    'satisfied',
                    'cancellationReason',
                    'relatedLot',
                    'tendererActionDate',
                ],
                'required',
            ],
            [
                [
                    'id',
                    'author_id',
                    'dateSubmitted',
                    'dateAnswered',
                    'dateEscalated',
                    'dateDecision',
                    'dateCancelled',
                    'satisfied',
                    'created_at',
                    'updated_at',
                ],
                'integer',
            ],
            [
                [
                    'description',
                    'resolution',
                    'decision',
                    'cancellationReason',
                ],
                'string',
            ],
            [
                [
                    'title',
                    'relatedLot',
                    'tendererAction',
                ],
                'string',
                'max' => 255,
            ],
            [
                [
                    'date',
                    'tendererActionDate',
                ],
                'string',
                'max' => 35,
            ],
            [
                [
                    'status',
                    'type',
                    'resolutionType',
                ],
                'string',
                'max' => 25,
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'author_id' => Yii::t('app', 'Author ID'),
            'title' => Yii::t('app', 'Title'),
            'description' => Yii::t('app', 'Description'),
            'date' => Yii::t('app', 'Date'),
            'dateSubmitted' => Yii::t('app', 'Date Submitted'),
            'dateAnswered' => Yii::t('app', 'Date Answered'),
            'dateEscalated' => Yii::t('app', 'Date Escalated'),
            'dateDecision' => Yii::t('app', 'Date Decision'),
            'dateCancelled' => Yii::t('app', 'Date Cancelled'),
            'status' => Yii::t('app', 'Status'),
            'type' => Yii::t('app', 'Type'),
            'resolution' => Yii::t('app', 'Resolution'),
            'resolutionType' => Yii::t('app', 'Resolution Type'),
            'satisfied' => Yii::t('app', 'Satisfied'),
            'decision' => Yii::t('app', 'Decision'),
            'cancellationReason' => Yii::t('app', 'Cancellation Reason'),
            'relatedLot' => Yii::t('app', 'Related Lot'),
            'tendererAction' => Yii::t('app', 'Tenderer Action'),
            'tendererActionDate' => Yii::t('app', 'Tenderer Action Date'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }
}
