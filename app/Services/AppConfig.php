<?php

namespace App\Services;

class AppConfig
{
    public const TOKEN_EXPIRE_TIME = 15;
    public const ADMIN = "admin";
    public const CUSTOMER = "customer";
    public const EDITOR = "editor";
    public const VERIFY_EMAIL = 1;
    public const VERIFY_PHONE = 2;
    public const TRANSACTION_TYPE_CREDIT = 'credit';
    public const TRANSACTION_TYPE_DEBIT = 'debit';
    public const TRANSACTION_TYPE_PLACE_BET = 'place_bet';
    public const TRANSACTION_TYPE_PLACE_BET_WON = 'place_bet_won';
    public const TRANSACTION_TYPE_PLACE_BET_DRAW = 'place_bet_draw';
    public const TRANSACTION_STATUS_WAITING = 'waiting';
    public const TRANSACTION_STATUS_EXPIRED = 'expired';
    public const TRANSACTION_STATUS_CANCELLED = 'canceled';
    public const TRANSACTION_STATUS_FINISHED = 'finished';
    public const DEFAULT_CURRENCY = 'usdt';
    public const BET_BASE_DOWN = 'base_down';
    public const BET_BASE_UP = 'base_up';
    public const BET_TARGET_DOWN = 'target_down';
    public const BET_TARGET_UP = 'target_up';
    public const BET_STATUS_PENDING = 'pending';
    public const BET_STATUS_WON = 'won';
    public const BET_STATUS_LOST = 'lost';
    public const BET_STATUS_DRAW = 'draw';
    public const BET_STATUS_NO_RESULT = 'no_result';
}
