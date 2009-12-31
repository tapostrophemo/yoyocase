class AddFlickrAccountInfo < ActiveRecord::Migration

  def self.up
    add_column :users, :flickr_userid, :string
  end

  def self.down
    remove_column :users, :flickr_userid
  end

end
