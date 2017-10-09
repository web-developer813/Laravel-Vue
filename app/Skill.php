<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Skill extends Model
{
    #skill endorsement
    public function skill_endorsements() {
        return $this->hasMany('App\SkillEndorsement');
    }
}
