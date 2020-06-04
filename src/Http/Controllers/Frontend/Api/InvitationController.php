<?php

namespace Modules\Core\Http\Controllers\Frontend\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Core\Http\Requests\Frontend\Invitation\InvitationTeamRequest;
use Modules\Core\Services\Frontend\UserInvitationService;

class InvitationController extends Controller
{
    /**
     * @param Request $request
     * @param UserInvitationService $userInvitationService
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|\Illuminate\Database\Eloquent\Collection
     */
    public function index(Request $request, UserInvitationService $userInvitationService)
    {
        $user = $request->user();
        return $userInvitationService->getAllByUser($user, ['paginate' => true]);
    }


    /**
     * @param Request $request
     * @param UserInvitationService $userInvitationService
     * @return bool|\Modules\Core\Models\Frontend\UserInvitation
     */
    public function store(Request $request, UserInvitationService $userInvitationService)
    {
        $user = $request->user();
        return $userInvitationService->createWithUser($user);
    }


    /**
     * @param Request $request
     * @param UserInvitationService $userInvitationService
     * @return bool|\Modules\Core\Models\Frontend\UserInvitation
     */
    public function info(Request $request, UserInvitationService $userInvitationService)
    {
        $user = $request->user();
        $invitation = $userInvitationService->getUnusedToken($user, ['exception' => false]);
        if (empty($invitation)) {
            $invitation = $userInvitationService->createWithUser($user);
        }
        return $invitation;

    }


    public function team(InvitationTeamRequest $request, UserInvitationService $userInvitationService)
    {
        return $userInvitationService->getInviteesByUser($request->user(), [
            'level' => $request->level,
            'allOptions' => ['paginate' => true],
        ]);
    }
}
