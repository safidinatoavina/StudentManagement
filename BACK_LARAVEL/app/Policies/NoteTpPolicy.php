<?php

namespace App\Policies;

use App\Models\NoteTp;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class NoteTpPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\NoteTp  $noteTp
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, NoteTp $noteTp)
    {
        //
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user,NoteTp $note_tp)
    {
        return (
            (
                $user->roles()->where('roles.id',3)->exists()
                &&
                $user->tps()->where('t_p_s.id',$note_tp->tp_id)->exists()
            )
            ||
            $user->roles()->where('roles.id',7)->exists()
        );
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\NoteTp  $noteTp
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, NoteTp $noteTp)
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\NoteTp  $noteTp
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, NoteTp $noteTp)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\NoteTp  $noteTp
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, NoteTp $noteTp)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\NoteTp  $noteTp
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, NoteTp $noteTp)
    {
        //
    }
}
