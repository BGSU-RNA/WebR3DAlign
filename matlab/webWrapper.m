function [] = webWrapper()

    sendNotification('apetrov@bgsu.edu', 'Test', 'content');

end


function sendNotification(email, subject, message)

    % adapted from http://www.mathworks.com/matlabcentral/fileexchange/20227

    % imports login and password variables
    import_config;

    %% Set up Gmail SMTP service
    setpref('Internet', 'E_mail', email);
    setpref('Internet', 'SMTP_Server', 'smtp.gmail.com');
    setpref('Internet', 'SMTP_Username', login);
    setpref('Internet', 'SMTP_Password', password);

    % Gmail server
    props = java.lang.System.getProperties;
    props.setProperty('mail.smtp.auth','true');
    props.setProperty('mail.smtp.socketFactory.class', 'javax.net.ssl.SSLSocketFactory');
    props.setProperty('mail.smtp.socketFactory.port','465');

    sendmail(email, subject, message)

end
