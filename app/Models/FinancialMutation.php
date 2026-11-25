<?php

namespace App\Models;

use App\Models\Mbi\MbiUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

class FinancialMutation extends Model
{
    use HasFactory;

    protected $table = 'financial_mutations';

    protected $fillable = [
        'entity',
        'transaction_date',
        'type',
        'category',
        'account_name',
        'amount',
        'description',
        'reference_no',
        'notes',
        'proof_file',
        'running_balance',
        'created_by',
    ];

    protected $casts = [
        'transaction_date' => 'date',
        'amount'           => 'decimal:2',
        'running_balance'  => 'decimal:2',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(MbiUser::class, 'created_by');
    }

    public function scopeEntity($query, string $entity)
    {
        return $query->where('entity', $entity);
    }

    public function scopePsikomapping($query)
    {
        return $query->where('entity', 'psikomapping');
    }

    public function scopeProsekar($query)
    {
        return $query->where('entity', 'prosekar');
    }

    /**
     * Recalculate running balance sequentially for an entity
     */
    public static function recalculateBalances(string $entity = 'psikomapping'): void
    {
        $records = static::where('entity', $entity)
            ->orderBy('transaction_date', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        $runningBalance = 0;
        foreach ($records as $record) {
            if ($record->type === 'income') {
                $runningBalance += (float) $record->amount;
            } else {
                $runningBalance -= (float) $record->amount;
            }

            if ((float) $record->running_balance !== (float) $runningBalance) {
                DB::table('financial_mutations')
                    ->where('id', $record->id)
                    ->update(['running_balance' => $runningBalance]);
            }
        }
    }
}
