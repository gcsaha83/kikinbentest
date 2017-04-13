<?php
namespace Kikinben\AdvancedCommission\Ui\Component\Listing\Column\Kikinbenadvancedcommissioncommissiontrackid;

class PageActions extends \Magento\Ui\Component\Listing\Columns\Column
{
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource["data"]["items"])) {
            foreach ($dataSource["data"]["items"] as & $item) {
                $name = $this->getData("name");
                $id = "X";
                if(isset($item["kikinben_advancedcommission_commissiontrack_id"]))
                {
                    $id = $item["kikinben_advancedcommission_commissiontrack_id"];
                }
                $item[$name]["view"] = [
                    "href"=>$this->getContext()->getUrl(
                        "adminhtml/kikinben_advancedcommission_commissiontrack_id/viewlog",["id"=>$id]),
                    "label"=>__("Edit")
                ];
            }
        }

        return $dataSource;
    }    
    
}
