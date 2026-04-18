<?php

namespace App\Services;

use App\Models\Account;
use App\Models\JournalEntry;
use App\Models\JournalLine;
use Illuminate\Support\Facades\DB;

class AccountingService
{
    /**
     * Create a new account in the chart of accounts
     */
    public function createAccount(string $name, string $type, ?int $parentId = null, ?string $code = null): Account
    {
        if (!$code) {
            $code = uniqid($type . '_');
        }

        return Account::create([
            'name' => $name,
            'type' => $type,
            'parent_id' => $parentId,
            'code' => $code,
        ]);
    }

    /**
     * Create a balanced double-entry journal
     */
    public function createJournalEntry(string $description, string $date, array $lines, ?string $reference = null): JournalEntry
    {
        return DB::transaction(function () use ($description, $date, $lines, $reference) {
            
            $totalDebit = 0;
            $totalCredit = 0;

            foreach ($lines as $line) {
                $totalDebit += floatval($line['debit'] ?? 0);
                $totalCredit += floatval($line['credit'] ?? 0);
            }

            // Ensure balanced entry
            if (round($totalDebit, 2) !== round($totalCredit, 2)) {
                throw new \Exception("Journal entry must be balanced. Debit: $totalDebit, Credit: $totalCredit");
            }

            if ($totalDebit == 0) {
                throw new \Exception("Journal entry cannot be zero.");
            }

            $entry = JournalEntry::create([
                'description' => $description,
                'date' => $date,
                'reference' => $reference,
            ]);

            foreach ($lines as $line) {
                JournalLine::create([
                    'journal_entry_id' => $entry->id,
                    'account_id' => $line['account_id'],
                    'debit' => $line['debit'] ?? 0,
                    'credit' => $line['credit'] ?? 0,
                    'description' => $line['description'] ?? null,
                ]);
            }

            return $entry;
        });
    }
}
