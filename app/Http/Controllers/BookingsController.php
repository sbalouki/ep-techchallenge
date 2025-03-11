<?php

namespace App\Http\Controllers;

use App\Booking;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class BookingsController extends Controller
{
    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $bookingId): JsonResponse
    {
        $booking = Booking::findOrFail($bookingId);

        $this->authorize('delete', $booking);

        $booking->delete();

        return response()->json([], Response::HTTP_NO_CONTENT);
    }
}
