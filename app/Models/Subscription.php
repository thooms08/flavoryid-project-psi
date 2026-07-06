<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model {
    protected $fillable = ['partner_id', 'invoice_number', 'amount', 'paket', 'status', 'payment_url'];
}