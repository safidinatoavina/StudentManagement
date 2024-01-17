<?php

namespace App\Policies;

use App\Models\Note;
use App\Models\User;
use App\Models\Matiere;
use Illuminate\Auth\Access\HandlesAuthorization;

class NotePolicy
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
        return (
            $user->roles()->where('roles.id',1)->exists() 
            ||
            $user->roles()->where('roles.id',2)->exists()
        );
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Note  $note
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Note $note)
    {
        return (
            $user->roles()->where('roles.id',1)->exists() 
            ||
            $user->roles()->where('roles.id',2)->exists()
            ||
            $user->matieres()->where('matieres.id',$note->matiere_id)->exists()
        );
    }



    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user,Note $note)
    {
        return (
            (
                $user->roles()->where('roles.id',3)->exists()
                &&
                $user->matieres()->where('matieres.id',$note->matiere_id)->exists()
            )
            ||
            $user->roles()->where('roles.id',7)->exists()

        );
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Note  $note
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Note $note)
    {
        return $user->roles()->where('roles.id',1)->exists();
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Note  $note
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Note $note)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Note  $note
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Note $note)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Note  $note
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Note $note)
    {
        //
    }
}
