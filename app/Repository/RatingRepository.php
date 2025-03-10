<?php

namespace STS\Repository;

use DB;
use Carbon\Carbon;
use STS\Models\Rating as RatingModel;

class RatingRepository
{
    public function getRating($user_from_id, $user_to_id, $trip_id)
    {
        $rate = RatingModel::where('user_id_from', $user_from_id);
        $rate->where('user_id_to', $user_to_id);
        $rate->where('trip_id', $trip_id);

        return $rate->first();
    }

    public function getRatings($user, $data = [])
    {
        // $inQuery = "id IN (SELECT id FROM availables_ratings WHERE user_id_to = '" . $user->id . "' )";

        $ratings = RatingModel::where('user_id_to', $user->id);

        $ratings->where('available', 1);

        // $ratings->whereRaw($inQuery);

        if (isset($data['value'])) {
            $value = parse_boolean($data['value']);
            $value = $value ? RatingModel::STATE_POSITIVO : RatingModel::STATE_NEGATIVO;
            $ratings->where('rating', $value);
        }

        // $ratings->where('created_at', '<=', Carbon::Now()->subDays(RatingModel::RATING_INTERVAL));

        $ratings->orderBy('created_at', 'desc');

        $pageNumber = isset($data['page']) ? $data['page'] : null;
        $pageSize = isset($data['page_size']) ? $data['page_size'] : null;

        return make_pagination($ratings, $pageNumber, $pageSize);
    }

    public function getRatingsCount($user, $data)
    {
        $value = parse_boolean($data['value']);
        $results = DB::select(DB::raw("SELECT count(*) AS 'count' FROM availables_ratings WHERE user_id_to = :user_id AND rating = :rating"), [
            'user_id' => $user->id,
            'rating' => $value,
        ]);
        if (count($results) && isset($results[0]->count)) {
            return $results[0]->count;
        }

        return 0;
    }

    public function getPendingRatings($user)
    {
        $ratings = RatingModel::where('user_id_from', $user->id);
        $ratings->where('voted', false);
        $ratings->with(['from', 'to', 'trip']);
        $ratings->where('created_at', '>=', Carbon::Now()->subDays(RatingModel::RATING_INTERVAL));

        return $ratings->get();
    }

    public function find($id)
    {
        return RatingModel::find($id);
    }

    public function findBy($key, $value)
    {
        return RatingModel::where($key, $value)->get();
    }

    public function create($user_from_id, $user_to_id, $trip_id, $user_to_type, $user_to_state, $hash)
    {
        $newRating = [
            'trip_id' => $trip_id,
            'user_id_from' => $user_from_id,
            'user_id_to' => $user_to_id,
            'rating' => null,
            'comment' => '',
            'voted' => false,
            'reply_comment_created_at' => null,
            'reply_comment' => '',
            'voted_hash' => $hash,
            'user_to_type' => $user_to_type,
            'user_to_state' => $user_to_state,
            'rate_at' => null,
        ];

        $newRating = RatingModel::create($newRating);

        return $newRating;
    }

    public function update($rateModel)
    {
        return $rateModel->save();
    }

    public function update_rating_availability($rateModel)
    {
        // CALL update_rating_availability(NEW.id, NEW.trip_id, NEW.user_id_from, NEW.user_id_to);
        $params = [$rateModel->id, $rateModel->trip_id, $rateModel->user_id_from, $rateModel->user_id_to];

        return DB::select('CALL update_rating_availability (?,?,?,?)', $params);
    }

}
