<?php

namespace app\models;


use yii\base\Model;

class OrganizerForm extends Model
{
	public $id;
	public $user_id;
	public $fio;
	public $at_org;
	public $org_type;
	public $phone;
	public $firma_full;
	public $inn;
	public $zkpo;
	public $u_address;
	public $f_address;
	public $member;
	public $member_phone;
	public $site;
	public $files;
    public $edrpou_bank;
    public $mfo;
    public $bank_name;
	


	public function rules()
    {
        return [
            [['user_id', 'fio', 'at_org', 'org_type', 'edrpou_bank', 'mfo', 'bank_name'], 'required'],
            [['user_id', 'org_type', 'inn', 'zkpo', 'files'], 'integer', 'min' => 6, 'max' => 25],
            [['fio', 'at_org', 'phone', 'fax', 'firma_full', 'u_address', 'f_address', 'member', 'member_phone', 'site'], 'string', 'max' => 255],
        ];
    }

	public function init(){
		$profile = new Profile;
	}

	public function register(){

		if(!$this->validate()){
			return false;
        }

		$this->clients->setAttributes(
			[
				'id' 			=> $this->id,
				'user_id' 		=> $this->user_id,
				'fio' 			=> $this->fio,
				'at_org' 		=> $this->at_org,
				'org_type' 		=> $this->org_type,
				'phone' 		=> $this->phone,
				'firma_full' 	=> $this->firma_full,
				'inn' 			=> $this->inn,
				'zkpo' 			=> $this->zkpo,
				'u_address' 	=> $this->u_address,
				'f_address' 	=> $this->f_address,
				'member' 		=> $this->member,
				'member_phone' 	=> $this->member_phone,

				'site' 			=> $this->site,
				'files' 		=> $this->files,
			]
		);
		return $this->profile->save();
	}

}