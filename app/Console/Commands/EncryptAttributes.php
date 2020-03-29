<?php

namespace App\Console\Commands;

use App\Models\Portfolio;
use App\Models\Share;
use Illuminate\Console\Command;
use Illuminate\Contracts\Encryption\DecryptException;

class EncryptAttributes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'yourshares:encrypt-attributes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Encrypt specified attributes in models.';

    protected $classes = [
        'Portfolio', 'Share',
    ];

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        foreach ($this->classes as $className) {
            $class = 'App\\Models\\'.$className;
            $collections = $class::all();

            foreach ($collections as $model) {
                foreach ($model->getEncryptable() as $attribute) {
                    $value = $model->$attribute;
                    $model->$attribute = $value;
                    $model->save();
                }
            }
        }
    }
}
