<?php

namespace App\Http\Controllers\user;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;


class UserController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $usuarios = User::all();

        return $this->showAll($usuarios);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $campos = $request->all();

        //Reglas de validación

        //nombre con campo requerido
        //email requerido, con formato valido de email y unico en la tabla user
        //campo requerido, con un minimo de 6 caracteres y debe ser confirmada, tenemos que recibir un campo password confirmation. 
        $rules = [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed'
        ];

        //Envio las reglas para la validación
        $this->validate($request, $rules);

        //Seteado y corrección de datos para la carga de un usuario nuevo

        //encriptar password
        $campos['password'] = bcrypt($request->password);
        //especificamos que un usuario nuevo nueva es un usuario verificado
        $campos['verified'] = user::USUARIO_NO_VERIFICADO;
        //creamos token de verificacion
        $campos['verification_token'] = User::generarVerificationToken();
        //asignamos que por defecto un usuario nuevo es regular y no admin
        $campos['admin'] = User::USUARIO_REGULAR;

 
        $usuario = User::create($campos);

        return $this->showOne($usuario, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    // Inyeccion de dependencia implicita para usuario, si existe un usuario con ese id traelo.
    public function show(User $user)
    {
        //obtener el usuario de manera comun
        //$usuario = User::find($id);

        //obtener el usuario y un error si no existe el resultado
        //NO ES NECESARIO SI SE REEMPLAZA EL ID PARAMETRO POR LO QUE ESTA ACTUALMENTE
        //$usuario = User::findOrFail($id);

        return $this->showOne($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        //Para saber a que usuario vamos a modificar, primero hay que obtener la instancia del usuario a actualizar.
        //$user = User::findOrFail($id);

        //Reglas de validación

        //con formato valido de email y unico en la tabla user, pero si el usuario envia su propio mail nuevamente esta regla fallaria porque ya esxiste en la base de datos, por esto debemos indicarle que el email debe ser unico exceptuando el id actual.
        //con un minimo de 6 caracteres y debe ser confirmada, tenemos que recibir un campo password confirmation. 
        //Verifico que el valor de admin este incluido en alguno de los dos valores presentados, que son justamentes la variables definidas en el modelo.

        $rules = [
            'email' => 'email|unique:users,email,' . $user->id,
            'password' => 'min:6|confirmed',
            'admin' => 'in:' . User::USUARIO_ADMINISTRADOR . ',' . User::USUARIO_REGULAR,
        ];

        //Envio las reglas para la validación
        $this->validate($request, $rules);

        //vamos campo por campo realizando la actualizacion, por ejemplo, si la peticion tiene un name vamos a actualizar el name
        if($request->has('name'))
        {
            $user->name = $request->name;
        }

        if($request->has('email'))
        {
            //Tengo que verificar si el mail recibido es diferente al que el usuario tiene actualmente
            if($user->email != $request->email)
            {
                //El usuario sera nuevamente usuario no verificado y tendremos que generarle un nuevo token

                $user->verified = User::USUARIO_NO_VERIFICADO;
                $user->verification_token = User::generarVerificationToken();
                $user->email = $request->email;
            }    

            //En caso de que el mail no haya cambiado no es necesario cambiarlo.
         }

        // Si recibimos una contraseña antes de asignarla debemos encriptarla  
        if($request->has('password'))
        {
            $user->password = bcrypt($request->password);
        }

        //un usuario puede convertirse en admin solo si es verificado (por ahora solo queda esta validación)
        if($request->has('admin'))
        {
            if(!$user->esVerificado())
            {
                return $this->errorResponse('Unicamente los usuarios verificados pueden cambiar su valor de administrador', 409);
            }

            $user->admin = $request->admin;
        }    

        //si todos los valores que envia el usuario son iguales tenemos que decirle que la peticion esta mal formada

        if(!$user->isDirty())
        {
            return $this->errorResponse('Se debe especificar al menos un valor diferente para actualizar', 422);
        }

        $user->save();

         return $this->showOne($user);
        
    }
 
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        $user->delete();

        return $this->showOne($user);
    }
}
