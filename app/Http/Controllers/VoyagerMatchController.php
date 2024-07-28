<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\GameMatch;
use Illuminate\Http\Request;
use TCG\Voyager\Facades\Voyager;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Http\Controllers\Voyager\VoyagerBaseController;
use App\Models\PlayerScore;
use Illuminate\Support\Facades\DB;

class VoyagerMatchController extends VoyagerBaseController
{
    public function show(Request $request, $id)
    {
        $slug = $this->getSlug($request);

        $dataType = Voyager::model('DataType')->where('slug', '=', $slug)->first();

        $isSoftDeleted = false;

        if (strlen($dataType->model_name) != 0) {
            $model = app($dataType->model_name);
            $query = $model->query();

            // Use withTrashed() if model uses SoftDeletes and if toggle is selected
            if ($model && in_array(SoftDeletes::class, class_uses_recursive($model))) {
                $query = $query->withTrashed();
            }
            if ($dataType->scope && $dataType->scope != '' && method_exists($model, 'scope' . ucfirst($dataType->scope))) {
                $query = $query->{$dataType->scope}();
            }
            $dataTypeContent = call_user_func([$query, 'findOrFail'], $id);
            if ($dataTypeContent->deleted_at) {
                $isSoftDeleted = true;
            }
        } else {
            // If Model doest exist, get data from table name
            $dataTypeContent = DB::table($dataType->name)->where('id', $id)->first();
        }

        // Replace relationships' keys for labels and create READ links if a slug is provided.
        $dataTypeContent = $this->resolveRelations($dataTypeContent, $dataType, true);

        // If a column has a relationship associated with it, we do not want to show that field
        $this->removeRelationshipField($dataType, 'read');

        // Check permission
        $this->authorize('read', $dataTypeContent);

        // Check if BREAD is Translatable
        $isModelTranslatable = is_bread_translatable($dataTypeContent);

        // Eagerload Relations
        $this->eagerLoadRelations($dataTypeContent, $dataType, 'read', $isModelTranslatable);

        $view = 'voyager::bread.read';

        if (view()->exists("voyager::$slug.read")) {
            $view = "voyager::$slug.read";
        }

        // Apartado para ver información de participantes de la partida

        $playerScores = PlayerScore::with(['player', 'team'])->where('match_id', $id)->get();
        $winner = null;
        $winningTeamId = null;
        if ($dataTypeContent->game_mode == 'free_for_all') {
            $winner = $playerScores->sortByDesc('points')->first();
        } else {
            $teamScores = $playerScores->groupBy('team_id')->map(function ($team) {
                return $team->sum('points');
            });
            $winningTeamId = $teamScores->sortDesc()->keys()->first();
        }
        return Voyager::view($view, compact('dataType', 'dataTypeContent', 'isModelTranslatable', 'isSoftDeleted', 'playerScores', 'winner', 'winningTeamId'));
    }
}
