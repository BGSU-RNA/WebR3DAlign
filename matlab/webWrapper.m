function [AlNTs1, AlNTs2] = webWrapper(pdb1,Chain1,Nts1, pdb2,Chain2,Nts2, Disc3,NeighMin3,Band3,CliqMeth3, Query, Al1,Al2)

    AlNTs1 = '';
    AlNTs2 = '';

    try
        Query.Type = 'web';

        if nargin == 11
            [AlNTs1, AlNTs2, ErrorMsg] = R3DAlign(pdb1,Chain1,Nts1, pdb2,Chain2,Nts2, Disc3,NeighMin3,Band3,CliqMeth3,Query);
        else
            [AlNTs1, AlNTs2, ErrorMsg] = R3DAlign(pdb1,Chain1,Nts1, pdb2,Chain2,Nts2, Disc3,NeighMin3,Band3,CliqMeth3,Query,Al1,Al2);
        end

        if ~strcmp(ErrorMsg, '')
            error(ErrorMsg);
        end

        if nargin == 11 && exist('Query') && isfield(Query, 'Email') && ~strcmp(Query.Email, '')
            subject = ['R3D Align results ' Query.Name];
            msg = successMessage(Query.Name);
            sendNotification({Query.Email}, subject, msg);
        end

    catch err

        log = errorLog(err, Query.Name);
        fprintf(log);

        % save error report
        fid = fopen([Query.Name '_error.txt'], 'w');
        fprintf(fid, '%s', log);
        fclose(fid);

        % notify the user
        if exist('Query') && isfield(Query, 'Email') && ~strcmp(Query.Email, '')
            subject = ['Problem with R3D Align query ' Query.Name];
            msg = errorMessage(Query.Name);
            sendNotification({Query.Email}, subject, msg);
        end

        % notify the admin
        importConfig;
        sendNotification(config.adminEmail, 'R3D Align error log', log);

        quit;

    end

    fprintf('\nDone\n');

end


function [report] = errorLog(err, queryId)

    importConfig;

    report = sprintf('Error message: %s\n', err.message);

    for e = 1:length(err.stack)
        report = sprintf('%sIn function %s at line number %i\n', report, err.stack(e).name, err.stack(e).line);
    end

    report = strcat(report, sprintf('\n%s/%s\n', config.resultsUrl, queryId));

end


function [msg] = successMessage(queryId)
    importConfig;
    msg = sprintf('Your R3D Align results are available at the following url: \n');
    msg = strcat(msg, [config.resultsUrl '/' queryId]);
end


function [msg] = errorMessage(queryId)
    importConfig;
    msg = 'An error occurred while processing your R3D Align query. ';
    msg = strcat(msg, 'You can view the report at the following url: ');
    msg = strcat(msg, [config.resultsUrl '/' queryId]);
end


function sendNotification(email, subject, message)

    % email is a cell array of emails
    % adapted from http://www.mathworks.com/matlabcentral/fileexchange/20227

    % imports login and password variables
    importConfig;

    for i = 1:length(email)

        %% Set up Gmail SMTP service
        setpref('Internet', 'E_mail', email{i});
        setpref('Internet', 'SMTP_Server', config.server);
        setpref('Internet', 'SMTP_Username', config.login);
        setpref('Internet', 'SMTP_Password', config.password);

        % Gmail server
        props = java.lang.System.getProperties;
        % props.setProperty('mail.smtp.auth','true');
        props.setProperty('mail.smtp.socketFactory.class', 'javax.net.ssl.SSLSocketFactory');
        props.setProperty('mail.smtp.socketFactory.port','465');

        sendmail(email{i}, subject, message);

    end

end
