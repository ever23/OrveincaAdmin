ALTER TABLE `pagos_fact` CHANGE `bsf_pago` `bsf_pago` DOUBLE(20,3) NOT NULL;
ALTER TABLE `coti_prod` CHANGE `prec_vent` `prec_vent` DECIMAL(20,3) NOT NULL;
ALTER TABLE `faco_prod` CHANGE `cost_comp` `cost_comp` DOUBLE(20,3) NOT NULL COMMENT 'precio de venta ';
ALTER TABLE `nent_prod` CHANGE `prec_vent` `prec_vent` DOUBLE(20,3) NOT NULL COMMENT 'precio de venta ';

ALTER TABLE `nomina` 
CHANGE `suel_diar` `suel_diar` DOUBLE(20,3) NOT NULL, 
CHANGE `comicion` `comicion` DOUBLE(20,3) NOT NULL, 
CHANGE `s_p_f` `s_p_f` DOUBLE(20,3) NOT NULL,
CHANGE `l_p_h` `l_p_h` DOUBLE(20,3) NOT NULL, 
CHANGE `s_o_s` `s_o_s` DOUBLE(20,3) NOT NULL, CHANGE `cest_tike` `cest_tike` DOUBLE(20,3) NOT NULL;

ALTER TABLE `orde_prod` CHANGE `cost_orde` `cost_orde` DOUBLE(20,3) NOT NULL COMMENT 'costo ';
ALTER TABLE `pedi_prod` CHANGE `prec_vent` `prec_vent` DOUBLE(20,3) NOT NULL COMMENT 'precion de venta del producto ';
ALTER TABLE `tama_prod` CHANGE `cost_tama` `cost_tama` DOUBLE(20,3) NOT NULL COMMENT 'costo relacionado ';
ALTER TABLE `temp_coti_prod` CHANGE `prec_vent` `prec_vent` DOUBLE(20,3) NOT NULL;
ALTER TABLE `tem_nent_prod` CHANGE `cost_orde` `cost_orde` DOUBLE(20,3) NOT NULL;

