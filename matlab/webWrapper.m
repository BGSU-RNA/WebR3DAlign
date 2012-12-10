function [AlNTs1, AlNTs2] = webWrapper(pdb1,Chain1,Nts1, pdb2,Chain2,Nts2, Disc3,NeighMin3,Band3,CliqMeth3, Query, Al1,Al2)

    try
        AlNTs1 = '';
        AlNTs2 = '';

        if nargin == 11
%            [AlNTs1, AlNTs2] = R3DAlign(pdb1,Chain1,Nts1, pdb2,Chain2,Nts2, Disc3,NeighMin3,Band3,CliqMeth3,Query);
        else
%            [AlNTs1, AlNTs2] = R3DAlign(pdb1,Chain1,Nts1, pdb2,Chain2,Nts2, Disc3,NeighMin3,Band3,CliqMeth3,Query,Al1,Al2);
        end

        if exist('Query') && isfield(Query, 'Email') && ~strcmp(Query.Email, '')
            subject = ['R3DAlign results ' Query.Name];
            msg = successMessage(Query.Name);
            sendNotification(Query.Email, subject, msg);
        end

        disp('OK');

    catch err
        disp(Query.Name);
        if ~exist('Query') || ~isfield(Query, 'Name') || strcmp(Query.Name, '')
            Query.Name = 'Unknown query id';
        end

        sendErrorReport(err, Query.Name);

    end

end


function sendErrorReport(err, queryId)

    importConfig;

    report = sprintf('%s/%s\n', config.resultsUrl, queryId);
    report = strcat(report, sprintf('Error message: %s\n', err.message));

    for e = 1:length(err.stack)
        report = sprintf('%sIn function %s at line number %i\n', report, err.stack(e).name, err.stack(e).line);
    end

    sendNotification(config.adminEmail, 'R3DAlign error log', report);
end


function [msg] = successMessage(queryId)
    importConfig();
    msg = sprintf('Your R3DAlign results are available at the following url: \n');
    msg = strcat(msg, [config.resultsUrl '/' queryId]);
end


function [msg] = errorMessage()
    importConfig();
    msg = 'An error occurred while processing your R3DAlign query. ';
    msg = strcat(msg, 'You can view the report at the following url: ');
    msg = strcat(msg, [config.resultsUrl '/' queryId]);
end


function sendNotification(email, subject, message)

    % adapted from http://www.mathworks.com/matlabcentral/fileexchange/20227

    % imports login and password variables
    importConfig;

    %% Set up Gmail SMTP service
    setpref('Internet', 'E_mail', email);
    setpref('Internet', 'SMTP_Server', 'smtp.gmail.com');
    setpref('Internet', 'SMTP_Username', config.login);
    setpref('Internet', 'SMTP_Password', config.password);

    % Gmail server
    props = java.lang.System.getProperties;
    props.setProperty('mail.smtp.auth','true');
    props.setProperty('mail.smtp.socketFactory.class', 'javax.net.ssl.SSLSocketFactory');
    props.setProperty('mail.smtp.socketFactory.port','465');

    sendmail(email, subject, message);

end
