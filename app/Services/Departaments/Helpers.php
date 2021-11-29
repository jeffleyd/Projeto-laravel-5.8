<?php


namespace App\Services\Departaments;


trait Helpers
{
	public $approv_receivers = ['glauco.leao@gree-am.com.br', 'joao.rocha@gree-am.com.br', 'simone@gree-am.com.br'];

    public function onlyBoss($arr, $immediates, $version, $start_position = 1) {

        foreach($immediates as $key) {

            array_push($arr,
                [
                    'version' => $version,
                    'r_code' => $key->r_code,
                    'position' => $start_position
                ]
            );
        }

        return $arr;
    }

    public function bossToBoss($arr, $immediates, $version, $start_position = 1) {

        $arr = $this->recursiveFuncImdts($arr, $immediates, $start_position, $version);
        $pos = count($arr) ? $arr[count($arr)-1]['position'] : 0;

        return [
            'arr_approv' => $arr,
            'last_position' => $pos
        ];
    }

    private function recursiveFuncImdts($arr, $immediates, $pos, $version) {

        if ($pos != 1)
            if ($immediates->where('r_code', '0004')->count())
                return $arr;

        foreach ($immediates as $index => $immediate) {
            array_push($arr, [
                'version' => $version,
                'r_code' => $immediate->r_code,
                'position' => $pos
            ]);

            if ($immediates->count() == ($index+1)) {
                $get_bosses = $immediate->immediates()->get();
                if ($get_bosses->count()) {
                    $pos++;
                    $arr = $this->recursiveFuncImdts($arr, $get_bosses, $pos, $version);
                }
            }
        }

        return $arr;
    }
}
