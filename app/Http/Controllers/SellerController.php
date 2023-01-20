<?php

namespace App\Http\Controllers;

use App\Models\Seller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SellerController extends Controller{


    //metodo __construct
    public function __construct(){
        $this->middleware('auth:api', ['except' => ['index', 'show', 'list']]);
    }

    /**
     * @OA\Post(
     *     path="/api/seller",
     *     summary="Convertirse en usuario vendedor",
     *     @OA\Response(
     *         response=200,
     *         description="Convertirse en usuario vendedor"
     *     ),
     *     @OA\Response(
     *         response="default",
     *         description="Ha ocurrido un error."
     *     )
     * )
     */
    //guardar un usuario seller
    public function store(Request $request){
        //validador
        $validator = Validator::make($request->all(), [
            'dni' => 'required|string|max:10',
            'account_number' => 'required',
            'user_id' => 'required'

        ]);
        //si no se puede validar el store de la direccion
        if($validator->fails()){
            return response()->json($validator->messages(), 400);
        }

        $seller = Seller::create([
            'dni' => $request->get('dni'),
            'account_number' => $request->get('account_number'),
            'user_id' => $request->get('user_id'),
        ]);

        return response()->json(['message'=>'Usario Seller Creado Satisfactoriamente','data'=>$seller],200);

    }


        /*Actualizar usuario vendedor*/
    public function update(Request $request){
            $user_id = Auth::user()->id;
            $user = Seller::where('user_id',$user_id)->get();
            print_r($user);
             exit();
             $user->save();
           /*  $user->update($request->all());*/
            return response()->json(['message' => 'Haz modificado tu usuario satisfactoriamente', 'data' => $user], 200);

    }


    /**
     * @OA\Post(
     *     path="/api/sellers",
     *     summary="Ver todos los usuarios vendedores por el admin",
     *     @OA\Response(
     *         response=200,
     *         description="Ver todos los usuarios vendedores por el admin"
     *     ),
     *     @OA\Response(
     *         response="default",
     *         description="Ha ocurrido un error."
     *     )
     * )
     */
    //todas las usuarios vendedores por el admin
    public function index(Request $request){
        $usertype=Auth::user()->usertype;
        //si el usuario esta iniciado sesion como admin, PUEDE GUARDAR UN PRODUCTO
        if($usertype=='1'){
            $sellers = Seller::all();
            return $sellers;
        }else{
            return response()->json(['message' => 'No estas autorizado para ver estos usuarios'], 500);
        }
    }


    //Eliminar order admin
    public function destroy(Request $request, $id){
        $usertype=Auth::user()->usertype;
        //si el usuario esta iniciado sesion como admin, PUEDE elminar UN PRODUCTO
        if($usertype=='1'){
            $seller = Seller::findOrFail($id);
            $seller->delete();
            return $seller;
        }else{
            return response()->json(['message' => 'No estas autorizado a  eliminar un usuario vendedor'], 500);
        }
    }


}
