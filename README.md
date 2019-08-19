Reproduce for https://github.com/phpredis/phpredis/issues/1622
=======================================

```bash
docker-compose up --build -d
docker-compose run php composer install

# get a coredump
docker exec -ti php-fpm-spanner_php_1 ash

## inside container
$ gdb /usr/local/bin/php
(gdb) source /.gdbinit
(gdb) run index.php
Starting program: /usr/local/bin/php index.php

Program received signal SIGSEGV, Segmentation fault.
i_zval_ptr_dtor (zval_ptr=0x7ffff78662a0) at /usr/src/php/Zend/zend_variables.h:48
48	/usr/src/php/Zend/zend_variables.h: No such file or directory.
(gdb) bt
#0  i_zval_ptr_dtor (zval_ptr=0x7ffff78662a0) at /usr/src/php/Zend/zend_variables.h:48
#1  zend_array_destroy (ht=0x7ffff785f460) at /usr/src/php/Zend/zend_hash.c:1310
#2  0x0000555555b0cc61 in i_zval_ptr_dtor (zval_ptr=<optimized out>) at /usr/src/php/Zend/zend_variables.h:49
#3  ZEND_DO_FCALL_SPEC_RETVAL_UNUSED_HANDLER () at /usr/src/php/Zend/zend_vm_execute.h:941
#4  execute_ex (ex=0x0) at /usr/src/php/Zend/zend_vm_execute.h:59765
#5  0x0000555555b0dc98 in zend_execute (op_array=0x7ffff787a540, op_array@entry=0x7ffff78758e0, return_value=0x0, return_value@entry=0x7ffff781c030) at /usr/src/php/Zend/zend_vm_execute.h:63776
#6  0x0000555555a6ba33 in zend_execute_scripts (type=type@entry=8, retval=0x7ffff781c030, retval@entry=0x0, file_count=file_count@entry=3) at /usr/src/php/Zend/zend.c:1498
#7  0x0000555555a09018 in php_execute_script (primary_file=<optimized out>) at /usr/src/php/main/main.c:2594
#8  0x0000555555b101d0 in do_cli (argc=2, argv=0x7ffff7ffe9a0) at /usr/src/php/sapi/cli/php_cli.c:1011
#9  0x00005555556d0f9a in main (argc=2, argv=0x7ffff7ffe9a0) at /usr/src/php/sapi/cli/php_cli.c:1403
(gdb) zbacktrace
[0x7ffff781c030] (main) /src/index.php:16
```