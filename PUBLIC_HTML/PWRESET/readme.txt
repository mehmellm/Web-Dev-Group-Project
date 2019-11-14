1.  User has forgotten their password.

    They click on changepw.html:

  brahe.canisius.edu/~meyer/CSC380/PWRESET/changepw.html

2.  This triggers the server to run   request_pw_reset.php
    which writes a record to the end of file "request.txt"
    with username, date and a refnum.

    It then mails a link to the user.  That link points to
    actual_reset_form.php

3.  User reads their email and clicks on the link.  They go
    into actual_reset_form.php and type in their password twice
    and click submit.   This requests the server to run ...

4.  accept_change.php which then compares the refnum and username
    and writes the new password somewhere.
