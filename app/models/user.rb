class User < ActiveRecord::Base

  acts_as_authentic do |authcfg|
    authcfg.require_password_confirmation = false
  end

  validates_presence_of :email

end
