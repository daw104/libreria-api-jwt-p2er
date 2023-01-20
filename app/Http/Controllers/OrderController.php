<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller{

    //metodo __construct
    public function __construct(){
        $this->middleware('auth:api', ['except' => ['index', 'show', 'list']]);
    }


    public function storeOrder(Request $request){
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
        ]);
        //si no se puede validar el evento
        if($validator->fails()){
            return response()->json($validator->messages(), 400);
        }
        $order = Order::create([
            'user_id' => $request->get('user_id'),
        ]);

        return response()->json(['message'=>'Orden creada satisfactoriamente','data'=>$order],200);
    }

    /**
     * @OA\Post(
     *     path="/api/order/{order}/products/{product}/order",
     *     summary="Realizar una compra por el usuario que inicio sesion",
     *     @OA\Response(
     *         response=200,
     *         description="Realizar una compra por el usuario que inicio sesion"
     *     ),
     *     @OA\Response(
     *         response="default",
     *         description="Ha ocurrido un error."
     *     )
     * )
     */
    public function registrarUnaComprar(Request $request, Order $order, Product $product){
        //return "entra";
        $quantity = '';
        if($request->quantity){
            $quantity = $request->quantity;
        }
        if($order->products()->save($product, array('quantity' => $quantity))){
            return response()->json(['message'=>'Se ha Ordenado un Producto satisfactoriamente','data'=>$product],200);
        }
        return response()->json(['message'=>'Error','data'=>null],400);
    }


    //Listar el usario asociado a una orden = admin
    public function listUser(Order $order){
        $usertype=Auth::user()->usertype;
        //si el usuario esta iniciado sesion como admin, PUEDE GUARDAR UN PRODUCTO
        if($usertype=='1'){
            $users = $order->users;
            return response()->json(['message'=>'Este usuario ha realiazdo esta compra','data'=>$users],200);
        }else{
            return response()->json(['message' => 'No estas autorizado para ver estos datos'], 500);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/orders/{id}/delete",
     *     summary="Eliminar una orden por id y por el admin",
     *     @OA\Response(
     *         response=200,
     *         description="Eliminar una orden por el admin"
     *     ),
     *     @OA\Response(
     *         response="default",
     *         description="Ha ocurrido un error."
     *     )
     * )
     */
    //Eliminar order admin
    public function destroy(Request $request, $id){
        $usertype=Auth::user()->usertype;
        //si el usuario esta iniciado sesion como admin, PUEDE elminar UN PRODUCTO
        if($usertype=='1'){
            $order = Order::findOrFail($id);
            $order->delete();
            return $order;
        }else{
            return response()->json(['message' => 'No estas autorizado a  eliminar una orden'], 500);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/orders/{id}/update",
     *     summary="Actualizar una orden por admin",
     *     @OA\Response(
     *         response=200,
     *         description="actualizar una orden por el admin"
     *     ),
     *     @OA\Response(
     *         response="default",
     *         description="Ha ocurrido un error."
     *     )
     * )
     */
    //actualizar order ==admin
    public function update(Request $request,  $id){
        $usertype = Auth::user()->usertype;
        //si el usuario esta iniciado sesion como admin, PUEDE actualizar UNA ORDEN
        if ($usertype == '1') {
            $order = Order::findOrFail($id);
            $order->update($request->all());
            return $order;
            return response()->json(['message' => 'Esta es la peticion modificada', 'data' => $order], 200);
        } else {
            return response()->json(['message' => 'No estas autorizado a  actualizar un producto'], 500);
        }

    }




    //listado de productos para una orden especifica:
    public function listProduct(Order $order){
        $products = $order->products;
        return response()->json(['message'=>'Estos son los productos para esta orden','data'=>$products],200);

    }


}
