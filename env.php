<?php

putenv('DISPLAY_ERROS_DETAIL='.true);

putenv('DATA_HORA_SISTEMA='.$dateExpire = (new \DateTime('America/Manaus'))->format('Y-m-d H:i:s'));

putenv('BD_POSTGRESQL_HOST=ec2-34-193-101-0.compute-1.amazonaws.com'); 
putenv('BD_POSTGRESQL_DBNAME=djadku26muf44');
putenv('BD_POSTGRESQL_USER=qoiwqynewwcxzo');
putenv('BD_POSTGRESQL_PASSWORD=c60eb672bf8b1d1a37a399893b1691161aafdef09b156f1bae3c882dd9906117');

putenv('BD_POSTGRESQL_PORT=5432');

putenv('JWT_SECRET_KEY= Projeto TechNurse');

// putenv('RECAPTCHA_ENABLED='.true);
// putenv('RECAPTCHA_SITE_KEY=');
// putenv('RECAPTCHA_SECRET_KEY=6LdblOQZAAAAADwkg6xIhbPlpEhwkCY1MKStPbQ3');