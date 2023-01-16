<div id="tmpx">
<?php 
   if($iop!='')
   {
      $data['dfrom']    =$dfrom;
      $data['dto']      =$dto;
      $data['jmlitem']  = $jmlitem;
      $data['isi']      = $isi;
      $data['detail']   = $detail;
      $data['supplier'] = $supplier;
      $this->load->view('opnew/vformupdate',$data);
   }
   else
   {
      if($ispb!='')
      {
         $data['jmlitem']  =$jmlitem;
         $data['isi']      =$isi;
         $data['detail']   =$detail;
         $data['tgl']      =$tgl;
         $this->load->view('opnew/vformpemenuhan',$data);
      }
      else
      {
         if($isi)
         {
            $data['isi']      =$isi;
            $data['detail']   =$detail;
            $data['jmlitem']  =$jmlitem;
            $this->load->view('opnew/vformlist',$data);
         }
         else
         {
            $this->load->view('opnew/vformlist');
         }
      }
   }
?>
</div>
