<?php

require_once 'report.php';
class Summary_categories extends Report
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getDataColumns()
    {
        return [$this->lang->line('reports_category'), $this->lang->line('reports_subtotal'), $this->lang->line('reports_total'), $this->lang->line('reports_tax'), $this->lang->line('reports_profit')];
    }

    public function getData(array $inputs)
    {
        $this->con->select('category, sum(subtotal) as subtotal, sum(total) as total, sum(tax) as tax, sum(profit) as profit');
        $this->con->from('sales_items_temp');
        $this->con->join('items', 'sales_items_temp.item_id = items.item_id');
        $this->con->where('sale_date BETWEEN "'.$inputs['start_date'].'" and "'.$inputs['end_date'].'"');
        if ($inputs['sale_type'] == 'sales') {
            $this->con->where('quantity_purchased > 0');
        } elseif ($inputs['sale_type'] == 'returns') {
            $this->con->where('quantity_purchased < 0');
        }
        $this->con->group_by('category');
        $this->con->order_by('category');

        return $this->con->get()->result_array();
    }

    public function getSummaryData(array $inputs)
    {
        $this->con->select('sum(subtotal) as subtotal, sum(total) as total, sum(tax) as tax, sum(profit) as profit');
        $this->con->from('sales_items_temp');
        $this->con->join('items', 'sales_items_temp.item_id = items.item_id');
        $this->con->where('sale_date BETWEEN "'.$inputs['start_date'].'" and "'.$inputs['end_date'].'"');
        if ($inputs['sale_type'] == 'sales') {
            $this->con->where('quantity_purchased > 0');
        } elseif ($inputs['sale_type'] == 'returns') {
            $this->con->where('quantity_purchased < 0');
        }

        return $this->con->get()->row_array();
    }
}
