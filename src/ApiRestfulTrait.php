<?php
/*
* @author Gilberto Prudêncio Vaz de Moraes <moraesdev@gmail.com>
* @copyright Copyright (c) 2017
* @category PHP Trait
* @version [1.0]
* @date     2017-10-10
*/
namespace Traits\Controllers;

use Illuminate\Http\Request;

trait ApiRestfulTrait
{
  // define a model object
  protected $Model                  = null;
  // define a model name
  protected $modelName              = "";
  // define a model name
  protected $modelNamespace         = "Entities";
  // define data to passing blade view
  protected $indexData              = [];
  //define the blade view to call
  protected $indexView              = "crud";
  //define a redirect route name to call
  protected $indexRedirectRouteName = "";
  //define a getMethodName for getData
  protected $modelGetMethodName     = "";

  /**
  * Returns a instance of Model with same Controller name, from Entities folder
  * @return [Model] [Entity Instance]
  */
  protected function Model(){
    if ($this->Model == null) {
      $classPath = str_replace_last("Http\Controllers",$this->modelNamespace,get_class());
      $class = $this->modelName ? str_replace_last((new \ReflectionClass($this))->getShortName(),$this->modelName,$classPath) : str_replace_last("Controller","",$classPath); //$modelName;
      $this->Model = new $class();
    }
    return $this->Model;
  }

  /**
  * GM - Display a listing of the resource.
  *
  * @return \Illuminate\Http\Response
  */
  public function index(Request $request)
  {
    if ($request->expectsJson() || ($request->get("json") != null && env('APP_DEBUG', false))) {
      return $this->getData($request);
    }
    return $this->indexRedirectRouteName ? redirect()->route($this->indexRedirectRouteName) : $this->getPage();
  }

  /*
  * getPage return a blade view
  * @param  Request $request
  * @return [BladeView]
  */
  private function getPage()
  {
    return view($this->indexView, $this->indexData);
  }

  /*
  * [getData description]
  * @param  Request $request  [description]
  * @param  integer $paginate [description]
  * @return [type]            [description]
  */
  private function getData(Request $request)
  {
    if ($this->modelGetMethodName) {
      return response()->json($this->Model()->{$this->modelGetMethodName}($request->all()),200);
    }
    return response()->json($this->Model()->paginate($request->get("limit")),200 );
  }

  /**
  * GM - Store a newly created resource in storage.
  *
  * @param  \Illuminate\Http\Request  $request
  * @return \Illuminate\Http\Response
  */
  public function store(Request $request)
  {
    if ($this->Model()->hasRules()) {
      if (!$this->Model()->validate($request->all())) {
        return response()->json($this->Model()->errors, 400);
      }
    }
    return response()->json($this->Model()->create($request->all()), 201);
  }

  /**
  * GM - Display the specified resource.
  *
  * @param  int  $id
  * @return \Illuminate\Http\Response
  */
  public function show($id)
  {
    return $this->Model()->findOrFail($id);
  }

  /**
  * GM - Update the specified resource in storage.
  *
  * @param  \Illuminate\Http\Request  $request
  * @param  int  $id
  * @return \Illuminate\Http\Response
  */
  public function update(Request $request, $id)
  {
    if ($this->Model()->hasRules()) {
      if (!$this->Model()->validate($request->all(), $id)) {
        return response()->json($this->Model()->errors, 400);
      }
    }
    return response()->json($this->Model()->findOrFail($id)->update($request->all()), 200);
  }

  /**
  * GM - Remove the specified resource from storage.
  *
  * @param  int  $id
  * @return \Illuminate\Http\Response
  */
  public function destroy(Request $request, $id)
  {
    return response()->json($this->Model()->findOrFail($id)->delete(), 204);
  }
}
