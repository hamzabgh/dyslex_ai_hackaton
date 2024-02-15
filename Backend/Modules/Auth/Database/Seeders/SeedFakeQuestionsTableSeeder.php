<?php

namespace Modules\Auth\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class SeedFakeQuestionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $easyWords = array(
            "And","Are","Ape","Ace","Act","Ask","Arm","Age","Ago","Air","Ate","All","But",
            "Bye","Bad","Big","Bed","Bat","Boy","Bus","Bag","Box","Bit","Bee","Buy","Bun",
            "Cub","Cat","Car","Cut","Cow","Cry","Cab","Can","Dad","Dab","Dam","Did","Dug",
            "Den","Dot","Dip","Day","Ear","Eye","Eat","End","Elf","Egg","Far","Fat","Few",
            "Fan","Fun","Fit","Fin","Fox","Fix","Fly","Fry","For","Got","Get","God","Gel",
            "Gas","Hat","Hit","Has","Had","How","Her","His","Hen","Ink","Ice","Ill","Jab",
            "Jug","Jet","Jam","Jar","Job","Jog","Kit","Key","Lot","Lit","Let","Lay","Mat",
            "Man","Mad","Mug","Mix","Map","Mum","Mud","Mom","May","Met","Net","New","Nap",
            "Now","Nod","Net","Not","Nut","Oar","One","Out","Owl","Old","Own","Odd","Our",
            "Pet","Pat","Peg","Paw","Pup","Pit","Put","Pot","Pop","Pin","Rat","Rag","Rub",
            "Row","Rug","Run","Rap","Ram","Sow","See","Saw","Set","Sit","Sir","Sat","Sob",
            "Tap","Tip","Top","Tug","Tow","Toe","Tan","Ten","Two","Use","Van","Vet","Was",
            "Wet","Win","Won","Wig","War","Why","Who","Way","Wow","You","Yes","Yak","Yet",
            "Zip","Zap"
        );

        // Insert words into the database with corresponding levels
        $this->insertWords($easyWords, 1);
    }

    private function insertWords($words, $level)
    {
        foreach ($words as $word) {
            $audioFile = $word . '.mp3';

            DB::table('questions')->insert([
                'world' => $word,
                'level' => $level,
                'audioFile' => $audioFile,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
