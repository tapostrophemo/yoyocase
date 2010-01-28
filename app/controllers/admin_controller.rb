require 'application.rb' # TODO: determine why is this needed on webhost?

class AdminController < ApplicationController
  def index
  end

  def show_accounts
    @users = User.find(:all)
  end
end
