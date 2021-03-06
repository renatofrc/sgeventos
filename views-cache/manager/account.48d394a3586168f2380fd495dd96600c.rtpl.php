<?php if(!class_exists('Rain\Tpl')){exit;}?><!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    Novo Evento
  </h1>
  <ol class="breadcrumb">
    <li><a href="/manager"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="/manager/events">Events</a></li>
    <li class="active"><a href="/manager/events/new">New</a></li>
  </ol>
</section>

<!-- Main content -->
<section class="content">

  <div class="row">
    <div class="col-md-12">
      <div class="box box-success">
        <div class="box-header with-border">
         
        </div>
        <!-- /.box-header -->
        <!-- form start -->
        <?php $counter1=-1;  if( isset($event) && ( is_array($event) || $event instanceof Traversable ) && sizeof($event) ) foreach( $event as $key1 => $value1 ){ $counter1++; ?>
        <form role="form" action="/manager/financial/request/<?php echo htmlspecialchars( $value1["idevent"], ENT_COMPAT, 'UTF-8', FALSE ); ?>/bank/" method="post">
          <?php } ?>
          <div class="box-body">
             <?php if( $registerError!='' ){ ?>
                <div class="alert alert-danger">
                    <?php echo htmlspecialchars( $registerError, ENT_COMPAT, 'UTF-8', FALSE ); ?>
                </div>
                <?php } ?>
            
            <div class="form-group">
              <label for="bank_name">Digite o nome do banco</label>
              <input type="text" class="form-control" id="bank_name" name="bank_name" class="form-control input-lg" type="text" placeholder="">
            </div>

            <div class="form-group">
              <label for="bank_name">Digite a agência (Sem dígito)</label>
              <input type="text" class="form-control" id="agency" name="agency" class="form-control input-lg" type="text" placeholder="">
            </div>

            <div class="form-group">
              <label for="bank_name">Digite a conta (Com dígito)</label>
              <input type="text" class="form-control" id="account" name="account" class="form-control input-lg" type="text" placeholder="">
            </div>

            <div class="form-group">
              <label for="bank_name">Digite o CPF ou CNPJ do titular</label>
              <input type="text" class="form-control" id="cpf_cnpj" name="cpf_cnpj" class="form-control input-lg" type="text" placeholder="">
            </div>

            <div class="form-group">
              <label for="bank_name">Digite o Nome do titular</label>
              <input type="text" class="form-control" id="holder_name" name="holder_name" class="form-control input-lg" type="text" placeholder="">
            </div>

            <div class="form-group">
              <label for="bank_name">Telefone para contato</label>
              <input type="text" class="form-control" id="phone" name="phone" class="form-control input-lg" type="text" placeholder="">
            </div>
           
           
          <!-- /.box-body -->
          <div class="box-footer">
            <button type="submit" class="btn btn-success">Adicionar conta</button>
          </div>
        </form>
      </div>
    </div>
  </div>

</section>
<!-- /.content -->
</div>
<!-- /.content-wrapper -->