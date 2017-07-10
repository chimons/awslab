sudo yum update –y
sudo yum install -y httpd24 php56
sudo service httpd start
sudo chkconfig httpd on
sudo groupadd www
sudo usermod -a -G www ec2-user
sudo chown -R root:www /var/www
sudo chmod 2775 /var/www
find /var/www -type d -exec sudo chmod 2775 {} +
find /var/www -type f -exec sudo chmod 0664 {} +
cd /var/www/html
wget https://github.com/chimons/awslab/archive/master.zip
unzip master.zip
mv awslab-master/* .
