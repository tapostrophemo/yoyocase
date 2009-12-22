class UsersController < ApplicationController

  def new
    @user = User.new
  end

  def create
    @user = User.new(params[:user])
    if @user.save
      flash[:msg] = "Welcome, #{@user.username}!"
      redirect_to root_url
    else
      render :action => "new"
    end
  end

  def edit
    @user = User.find(params[:id])
  end

  def update
    @user = User.find(params[:id])
    if @user.update_attributes(params[:user])
      flash[:msg] = "Preferences updated successfully"
      redirect_to account_url
    else
      render :action => "edit"
    end
  end

end
