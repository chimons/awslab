sudo yum update –y
sudo yum install -y httpd php56
sudo amazon-linux-extras install -y php7.2
maxsize=25M
sudo sed -i 's/upload_max_filesize = .*/upload_max_filesize = '${maxsize}'/' /etc/php.ini
sudo yum install -y php-xml
sudo systemctl restart php-fpm
sudo service httpd start
sudo chkconfig httpd on
sudo groupadd www
sudo usermod -a -G www ec2-user
sudo su ec2-user
sudo chown -R root:www /var/www
sudo chmod 2775 /var/www
find /var/www -type d -exec sudo chmod 2775 {} +
find /var/www -type f -exec sudo chmod 0664 {} +
cd /var/www/html
wget https://github.com/chimons/awslab/archive/refs/heads/master.zip
unzip -q master.zip
mv master/* .
rm master.zip
rm -R master/ install/
printf '\033[0;32m  Installation of the AWS LAB environment completed\e[m\n';
printf '\033[0;32m  Now, please update the index.php file with your actual S3 Bucket name and region.\e[m\n';
printf '\033[0;32m  You will also need to provide your API Gateway URL in apigatewayclient.js\e[m\n';
printf '\033[0;32m Then you will be able to access your application thru your browser at : \033[1;32m';
curl -s http://169.254.169.254/latest/meta-data/public-hostname
printf '\e[m\n\n'
