<?php

namespace App\Traits;

use App\Models\RefreshToken;

trait RefreshTokenTrait {
  public function generateRefreshToken(){
    $bytes = random_bytes(20);
    $check = RefreshToken::where('user_id', $this->id)->first();
    if($check){
      return $check;
    }else{
      $refresh_token = new RefreshToken();
      $refresh_token->user_id = $this->id;
      $refresh_token->token = bin2hex($bytes);
      $refresh_token->save();
      return $refresh_token;
    }
  }

  public function deleteRefreshToken(): mixed {
    $check = RefreshToken::where('user_id', $this->id)->first();
    if($check){
      $check->delete();
      return true;
    }else{
      return true;
    }
  }
}